-- Active: 1669226883728@@127.0.0.1@3306@laravel
<script setup>
import Navbar from '@/Components/Navbar.vue';
import { ref } from 'vue';


defineProps([

    'formName',
    'questionsName',
    'answers'

])

const data = ref([

    {
        name: 'test'
    }

])

function submit(params) {


    console.log(data.value);
}


</script>

<template>

    <!-- <h3>
        {{questionsName}}
    </h3> -->

    <Navbar :login="$page.props.auth.user">
    </Navbar>
    <div class="container">
        <div class="d-flex flex-column align-items-center mt-3">

            <div class="">
                <h1>
                    {{ formName }}
                </h1>
            </div>

            <div v-for="(questionName, index) in questionsName" :key="index">

                <div class="card mt-3" style="width: 25rem;">
                    <div class="card-body">
                        <h4 class="">
                            {{ questionName.name }}
                        </h4>

                        <div v-for="answer in answers[index]['answer']">
                            <div class="">
                                <div class="form-check">
                                    <input @click="submit" class="form-check-input" type="radio"
                                        :name="`${questionName.name}${index}`" :id="`${answer.name}${answer.id}`">
                                    <label class="form-check-label" :for="`${answer.name}${answer.id}`">
                                        {{ answer.name }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group mt-5">
                <button @click="submit" type="button" class="btn btn-primary btn-lg">Submit</button>
            </div>

        </div>
    </div>

</template>
