<template>

    <v-card
            class="mx-auto"
            dark
            max-width="400"
    >
        <v-card-title primary-title>
            <h3>
                <span class="title">{{name}}</span>
            </h3>
        </v-card-title>

        <v-card-text class="font-weight-bold">
            "Turns out semicolon-less style is easier and safer in TS because most gotcha edge cases are type invalid as
            well."
        </v-card-text>

        <v-card-actions>
            <v-list-tile class="grow">
                <v-btn dark >Atualizar</v-btn>
            </v-list-tile>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props: ['name']
    }
</script>
