#ifndef _BANKSIMULATION_H
#define _BANKSIMULATION_H	1

#define CHAIR_AMOUNT 5

#include <pthread.h>
#include <glib.h>
#include <semaphore.h>

typedef struct _chair_t chair_t;
typedef struct _client_t client_t;
typedef struct _manager_t manager_t;
typedef struct _state_t state_t;

struct _state_t
{
    // Mutability lock
    pthread_mutex_t *lock;

    manager_t *manager;
    GQueue *queue;
    chair_t *chairs[CHAIR_AMOUNT];
};

struct _client_t
{
    char *name;
    int work_time;
};
struct _manager_t
{
    // Mutability lock
    pthread_mutex_t *lock;
    // Client Arrives Notification
    sem_t *client_notification;

    client_t *client;
    state_t *state;

    pthread_t thread;

    int stop_working_flag;
};

struct _chair_t
{
    // Mutability lock
    pthread_mutex_t *lock;
    // Manager calling
    sem_t *manager_call;
    // index of the chair
    state_t *state;
    unsigned int index;
    pthread_t thread;
    client_t *client;
};

state_t *create_state();
void client_arrives(state_t *state, char *name, int expected_work_in_seconds);
void start_chair_thread(chair_t *chair);
void next_client(state_t *state);
int sit(state_t *state, client_t *client, chair_t *chair);
void chair_handler(chair_t *chair);
void manager_handler(state_t *state);
void start_manager_service(state_t *state);
void start_chair_thread(chair_t *chair);
manager_t *create_manager(state_t *state);
chair_t *create_chair(unsigned int index, state_t *state);
#endif