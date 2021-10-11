<template>
    <v-content app>
        <v-container fill-height>
            <v-layout justify-center align-center row>
                <v-flex xl3 lg4 sm6 xs12>
                    <v-card class="pa-2" >
                        <v-container justify-center>
                            <div>
                                <v-layout justify-center column>
                                    <v-layout row justify-center class="pb-5">
                                        <p class="display-1 font-weight-light primary--text">Login</p>
                                    </v-layout>
                                    <v-form v-model="valid" ref="form">
                                        <v-layout column>
                                            <v-flex>
                                                <v-text-field
                                                        :rules="usernameRules"
                                                        v-model="username"
                                                        :error-messages="errors"
                                                        @input="errors = []"
                                                        append-icon="face"
                                                        label="Usuário">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex>
                                                <v-text-field
                                                        :rules="passwordRules"
                                                        v-model="password"
                                                        :type="showPassword ? 'text' : 'password'"
                                                        @click:append="showPassword = !showPassword"
                                                        @keyup.enter="submit"
                                                        :append-icon="showPassword ? 'visibility' : 'visibility_off'"
                                                        label="Senha">
                                                </v-text-field>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout row wrap justify-end>
                                            <v-flex sm4>
                                                <v-btn
                                                        block
                                                        color="primary"
                                                        @click="submit">
                                                    Login
                                                </v-btn>
                                            </v-flex>
                                        </v-layout>

                                    </v-form>
                                </v-layout>

                            </div>
                        </v-container>
                    </v-card>
                </v-flex>
            </v-layout>

        </v-container>
    </v-content>


</template>
<script>
    export default {
        name: "login-form",
        props: ['errors'],
        data() {
            return {
                valid: false,
                showPassword: false,
                username: '',
                password: '',
                alert: false,
                usernameRules: [
                    v => !!v || 'Você precisa informar um usuario',
                ],

                passwordRules: [
                    v => !!v || 'Você precisa informar um usuario',
                ],
            }
        },
        methods: {
            submit() {
                if (this.$refs.form.validate()) {
                    console.log('Show');
                    redirectPost('/login', {
                        'username': this.username,
                        'password': this.password
                    });
                }
            },
        }

    }
</script>
