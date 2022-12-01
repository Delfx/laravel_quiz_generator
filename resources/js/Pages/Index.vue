<script setup>
import Navbar from '@/Components/Navbar.vue';
import { Inertia } from '@inertiajs/inertia'
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    allQuizForms: Array,
    allQuestions: Array
})

function deleteQuestion(quizId) {

    // console.log(id);


    Inertia.delete(route("deleteQuestion", quizId));

}

</script>


<template>

    <Navbar :login="$page.props.auth.user">
    </Navbar>


    <div class="container mt-5">

        <Link href="/quiz" class="btn btn-success m-2">Create quiz</Link>

        <div v-if="$page.props.auth.user">
            <div v-for="(formName, index) in allQuizForms" :key="index">

                <div>
                    {{formName}}
                </div>

                <div class="card mt-3 col-md-8 d-flex mx-auto">
                    <div class="card-header">
                        {{ formName.name }}
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">{{ formName.name }}</h3>
                        <p class="card-text m-0 p-0">Questions in form: {{ allQuestions[index].questions.length }}</p>
                        <a class="card-text" :href="`quiz/showQuestion/${formName.link}`">
                            <p class="card-text">Link: {{ `quiz/showQuestion/${formName.link}` }}</p>
                        </a>

                        <a :href="`quiz/showResults/${formName.id}`" class="btn btn-warning m-1">Show Results</a>

                        <button @click="deleteQuestion(formName.id)" class="btn btn-danger m-1">Delete</button>

                        <button :href="`quiz/editQuestion/${formName.user_id}`" class="btn btn-primary m-1"
                            disabled>Edit</button>

                    </div>
                </div>
            </div>
        </div>
    </div>




</template>
