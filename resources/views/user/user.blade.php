@extends('layouts.userapp')
@section('content')
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="blue darken-3 white--text"><h2>User</h2></v-card-title>
                    <user-course vimeourl="{{$vimeo_url}}" distinct="{{$distinct}}" vimeoid="{{$vimeo_id}}"></user-course>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
@endsection
