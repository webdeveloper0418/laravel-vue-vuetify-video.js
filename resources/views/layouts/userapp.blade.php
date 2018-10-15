<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user" content="{{ Auth::user() }}">
    <link rel="manifest" href="/manifest.json">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-corner-indicator.min.css" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<v-app id="app" v-cloak>
    <snackbar></snackbar>
    <v-toolbar
            color="blue darken-3"
            dark
            app
            clipped-left
            clipped-right
            fixed
    >
        <v-toolbar-title :style="$vuetify.breakpoint.smAndUp ? 'width: 300px; min-width: 250px' : 'min-width: 72px'" class="ml-0 pl-3">
            <span class="hidden-xs-only">User Course</span>
        </v-toolbar-title>
        <div class="d-flex align-center" style="margin-left: auto">
            <v-btn icon large @click="toogleRightDrawer">
                <gravatar :user="{{ Auth::user() }}" size="52px"></gravatar>
            </v-btn>
        </div>
    </v-toolbar>
    <v-navigation-drawer
            fixed
            v-model="drawerRight"
            right
            clipped
            app
    >
        <v-card>
            <v-container fluid grid-list-md class="grey lighten-4">
                <v-layout row wrap>
                    <v-flex xs12>
                        <gravatar :user="{{ Auth::user() }}" size="100px"></gravatar>
                    </v-flex>
                    <v-flex xs12>
                        <h2>@{{  user.name }}</h2>
                        <!-- <a href="https://en.gravatar.com/connect/">Change Avatar</a> -->
                    </v-flex>
                </v-layout>
            </v-container>
            <v-card-text class="px-0 grey lighten-3">
                <v-form class="pl-3 pr-1 ma-0">
                    <v-text-field :readonly="!editingUser"
                                  label="Email"
                                  :value="user.email"
                                  ref="email"
                                  @input="updateEmail"
                    ></v-text-field>
                    <v-text-field :readonly="!editingUser"
                                  label="User name"
                                  :value="user.name"
                                  @input="updateName"
                    ></v-text-field>
                    <v-text-field readonly
                                  label="Created at"
                                  :value="user.created_at"
                                  readonly
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn :loading="updatingUser" flat color="green" @click="updateUser" v-if="editingUser">
                    <v-icon right dark>save</v-icon>
                    Save
                </v-btn>
                <v-btn flat color="orange" @click="editUser()" v-else>
                    <v-icon right dark>edit</v-icon>
                    Edit
                </v-btn>
                <v-btn :loading="logoutLoading" @click="logout" flat color="orange">
                    <v-icon right dark>exit_to_app</v-icon>
                    Logout</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
            <v-card-actions>
                <v-spacer></v-spacer>
                <!-- <v-btn :loading="changingPassword" flat color="red" @click="changePassword">Change Password</v-btn> -->
                <v-spacer></v-spacer>
            </v-card-actions>
            <v-card-actions>
                <v-spacer></v-spacer>
                <!-- <a href="https://en.gravatar.com/connect/">Change Avatar</a> -->
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-navigation-drawer>
    <v-content>
        @yield('content')
    </v-content>
    
</v-app>

@stack('beforeScripts')
<script src="{{ mix('js/app.js') }}"></script>
<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>

@stack('afterScripts')
</body>
</html>
