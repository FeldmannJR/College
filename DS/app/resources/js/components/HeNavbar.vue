<template>
  <v-toolbar dark class="heNavbar" app>
    <v-toolbar-title class="white--text title font-weight-black">
      <a class="home" href="/">
        <img class="logo" :src="logo" />Estatisticas Hospital Escola
      </a>
    </v-toolbar-title>
    <v-spacer></v-spacer>

    <v-toolbar-items class="hidden-sm-and-down">
      <v-btn v-for="item in onlyNonHidden" :key="item.name" :href="item.to" flat>
        <v-icon left>{{item.icon}}</v-icon>
        {{item.name}}
      </v-btn>
    </v-toolbar-items>
    <v-menu offset-y class="hidden-sm-and-down">
      <v-toolbar-side-icon slot="activator"></v-toolbar-side-icon>
      <v-list>
        <v-subheader>Navegação</v-subheader>
        <div v-for="item in onlyHidden" :key="item.name">
          <v-divider></v-divider>
          <v-list-tile :href="item.to!= null ? item.to: ''" flat>
            <v-list-tile-content>
              <v-list-tile-title>{{item.name}}</v-list-tile-title>
            </v-list-tile-content>
            <v-list-tile-action>
              <v-icon v-if="item.icon != null">{{item.icon}}</v-icon>
            </v-list-tile-action>
          </v-list-tile>
        </div>
      </v-list>
    </v-menu>

    <v-menu offset-y class="hidden-md-and-up">
      <v-toolbar-side-icon slot="activator"></v-toolbar-side-icon>
      <v-list>
        <v-subheader>Navegação</v-subheader>
        <div v-for="item in items" :key="item.name">
          <v-divider></v-divider>
          <v-list-tile :href="item.to!= null ? item.to: ''">
            <v-list-tile-content>
              <v-list-tile-title>{{item.name}}</v-list-tile-title>
            </v-list-tile-content>
            <v-list-tile-action>
              <v-icon v-if="item.icon != null">{{item.icon}}</v-icon>
            </v-list-tile-action>
          </v-list-tile>
        </div>
      </v-list>
    </v-menu>
  </v-toolbar>
</template>

<script>
import "./HeNavbar.scss";
import logo from "../logo-he.png";

export default {
  props: ["user", "userRoles"],
  data() {
    return {
      //Screen,Statistics,Admin,Root
      items: [
        {
          name: "Painel",
          icon: "airplay",
          to: "/panel",
          role: this.userRoles.Screen
        },
        {
          name: "Apresentação",
          icon: "view_quilt",
          to: "/settings",
          role: this.userRoles.Admin
        },
        {
          name: "Relatorios",
          icon: "receipt",
          to: "/report",
          role: this.userRoles.Statistics
        },
        {
          name: "Manutenção",
          icon: "build",
          hidden: true,
          to: "/maintenance",
          role: this.userRoles.Root
        },
        {
          name: "Planilhas",
          icon: "library_books",
          hidden: true,
          to: "/planilhas",
          role: this.userRoles.Admin
        },
        {
          name: "Logout",
          icon: "subdirectory_arrow_right",
          hidden: true,
          to: "/logout"
        }
      ],
      logo: logo
    };
  },
  computed: {
    filterRoles() {
      let t = this;
      return this.items.filter(function(item) {
        return (
          item.role === undefined ||
          item.role === null ||
          t.user.user_role >= item.role.value
        );
      });
    },
    onlyHidden() {
      return this.filterRoles.filter(function(item) {
        return item.hidden !== null && item.hidden;
      });
    },
    onlyNonHidden() {
      return this.filterRoles.filter(function(item) {
        return !item.hidden;
      });
    }
  }
};
</script>

<style scoped>
</style>