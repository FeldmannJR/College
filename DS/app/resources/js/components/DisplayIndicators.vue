<template>
    <v-container fluid grid-list-md>
    <v-data-iterator
            :items="indicators"
            :rows-per-page-items="rowsPerPageItems"
            :pagination.sync="pagination"
            content-tag="v-layout"
            row
            wrap
    >
        <template v-slot:item="props">
            <v-flex
                    xs12
                    sm6
                    md4
                    lg3
            >
                <v-card>
                    <v-card-title><h4>{{ props.item.name }}</h4></v-card-title>
                    <v-divider></v-divider>
                    <v-list dense>
                        <v-list-tile>
                            <v-list-tile-content>Nome:</v-list-tile-content>
                            <v-list-tile-content class="align-end">{{ props.item.name }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                            <v-list-tile-content>Update:</v-list-tile-content>
                            <v-list-tile-content class="align-end">{{ props.item. }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                            <v-list-tile-content>Carbs:</v-list-tile-content>
                            <v-list-tile-content class="align-end">{{ props.item.carbs }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                            <v-list-tile-content>Protein:</v-list-tile-content>
                            <v-list-tile-content class="align-end">{{ props.item.protein }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                            <v-list-tile-content>Sodium:</v-list-tile-content>
                            <v-list-tile-content class="align-end">{{ props.item.sodium }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                            <v-list-tile-content>Calcium:</v-list-tile-content>
                            <v-list-tile-content class="align-end">{{ props.item.calcium }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                            <v-list-tile-content>Iron:</v-list-tile-content>
                            <v-list-tile-content class="align-end">{{ props.item.iron }}</v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>
        </template>
    </v-data-iterator>
    </v-container>
</template>

<script>
    export default {
        props: ['indicators']
    }
</script>

<style scoped>

</style>