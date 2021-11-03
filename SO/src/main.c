#include <stdio.h>
#include <pthread.h>
#include <glib.h>
#include <semaphore.h>
#include "main.h"

pthread_mutex_t *create_lock()
{
    pthread_mutex_t *lock = malloc(sizeof(pthread_mutex_t));
    if (pthread_mutex_init(lock, NULL) != 0)
    {
        exit(1);
        return NULL;
    }
    return lock;
}

int main()
{
    g_info("Eae pessoal");
    state_t *state = create_state();
    start_manager_service(state);

    client_arrives(state, "Kleber", 2);
    client_arrives(state, "Carlos", 3);
    client_arrives(state, "João", 2);
    client_arrives(state, "Ednaldo", 2);
    client_arrives(state, "Leonardo", 4);
    client_arrives(state, "Valdir", 5);
    client_arrives(state, "Osmar", 5);
    client_arrives(state, "Ligma", 5);
    sleep(3);
    client_arrives(state, "Eduarda", 5);
    sleep(1);
    client_arrives(state, "Leticia", 5);
    sleep(30);
    client_arrives(state, "Gerson", 20);
    state->manager->stop_working_flag = 1;
    pthread_join(state->manager->thread, NULL);
}
state_t *create_state()
{
    GQueue *queue = g_queue_new();
    state_t *state = malloc(sizeof(state_t));
    state->queue = queue;
    state->lock = create_lock();
    manager_t *manager = create_manager(&state);
    state->manager = manager;

    printf("There is a total of %i chairs!\n", CHAIR_AMOUNT);
    for (int i = 0; i < CHAIR_AMOUNT; i++)
    {
        chair_t *chair = create_chair(i, &state);
        state->chairs[i] = chair;
    }
    return state;
}

chair_t *create_chair(unsigned int index, state_t *state)
{
    pthread_mutex_t *lock = create_lock();

    sem_t *manager_call = malloc(sizeof(sem_t));
    sem_init(manager_call, 0, 0);

    chair_t *chair = malloc(sizeof(chair_t));
    chair->index = index;
    chair->client = NULL;
    chair->lock = lock;
    chair->state = state;
    chair->manager_call = manager_call;
    return chair;
}

manager_t *create_manager(state_t *state)
{
    pthread_mutex_t *lock = create_lock();
    sem_t *client_notification = malloc(sizeof(sem_t));
    sem_init(client_notification, 0, 0);
    manager_t *manager = malloc(sizeof(manager_t));
    manager->client = NULL;
    manager->client_notification = client_notification;
    manager->lock = lock;
    manager->stop_working_flag = 0;
    manager->state = state;
}
void client_arrives(state_t *state, char *name, int expected_work_in_seconds)
{
    // build client
    client_t *client = malloc(sizeof(client_t));
    client->name = name;
    client->work_time = expected_work_in_seconds;

    for (int i = 0; i < CHAIR_AMOUNT; i++)
    {
        chair_t *chair = state->chairs[i];
        pthread_mutex_lock(chair);
        if (chair->client == NULL)
        {
            // Cadeira vazia
            sit(state, client, chair);
            pthread_mutex_unlock(chair);
            return;
        }
        pthread_mutex_unlock(chair);
    }
    printf("😡 %s não achou cadeira vazia e foi embora!\n", name);
}

int sit(state_t *state, client_t *client, chair_t *chair)
{

    if (chair->client != NULL)
    {
        return 1;
    }
    chair->client = client;
    start_chair_thread(chair);

    // Add to the queue
    pthread_mutex_lock(state->lock);
    g_queue_push_tail(state->queue, chair);
    pthread_mutex_unlock(state->lock);

    printf("🪑 %s chegou e sentou na cadeira %i!\n", client->name, chair->index);

    // Notifies manager that a new client arrived
    sem_post(state->manager->client_notification);

    chair->thread = NULL;
}
void start_chair_thread(chair_t *chair)
{
    if (chair->thread != NULL)
    {
        pthread_cancel(chair->thread);
        return;
    }
    pthread_create(&chair->thread, NULL, chair_handler, chair);
}

void chair_handler(chair_t *chair)
{
    sem_wait(chair->manager_call);
    pthread_mutex_lock(chair->lock);
    printf("✨ Cadeira %i agora está livre!\n", chair->index);
    chair->client = NULL;
    pthread_mutex_unlock(chair->lock);
}

void start_manager_service(state_t *state)
{
    manager_t *manager = state->manager;
    pthread_create(&manager->thread, NULL, manager_handler, state);
}

void manager_handler(state_t *state)
{
    manager_t *manager = state->manager;

    while (1)
    {
        // Wait until client arrives
        sem_wait(manager->client_notification);
        pthread_mutex_lock(manager->lock);
        if (manager->client == NULL)
        {
            pthread_mutex_lock(state->lock);
            chair_t *chair = g_queue_pop_head(state->queue);
            pthread_mutex_unlock(state->lock);
            if (chair != NULL)
            {
                manager->client = chair->client;
                // Notifies chair to come to manager table
                sem_post(chair->manager_call);

                printf("👋 Gerente atendendo o client %s !\n", manager->client->name);
                sleep(manager->client->work_time);
                printf("🚶Finalizado atendimento do cliente %s!\n", manager->client->name);
                free(manager->client);
                manager->client = NULL;

                pthread_mutex_lock(state->lock);
                int remaining = g_queue_get_length(state->queue);
                pthread_mutex_unlock(state->lock);
                if (remaining == 0 && manager->stop_working_flag == 1)
                {
                    printf("🍺 Não tem mais nenhum cliente para atender e acabou o turno do gerente!\n");
                    break;
                }
            }
        }
        pthread_mutex_unlock(manager->lock);
    }
}
