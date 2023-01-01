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

        <div v-if="(allQuizForms.length == 0)">
            <h1 class="mt-3">
                Create your first Quiz
            </h1>
        </div>


        <div v-if="$page.props.auth.user">

            <div v-if="$page.props.auth.user" class="mt-3 col-md-8 d-flex mx-auto">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search" aria-label="Search"
                        aria-describedby="button-addon2">
                    <!-- <button class="btn btn-outline-primary" type="button" id="button-addon2">Search</button> -->
                </div>
            </div>


            <div v-for="(formName, index) in allQuizForms" :key="index">

                <div class="card mt-3 col-md-8 d-flex mx-auto">
                    <div class="card-header">
                        {{ formName.name }}
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">{{ formName.name }}</h3>
                        <p class="card-text m-0 p-0">Questions in form: {{ allQuestions[index].questions.length }}</p>
                        <div class="mt-2 mb-2">
                            <h6 class="m-0">Link:</h6>
                            <a class="card-text" :href="`quiz/showQuestion/${formName.link}`">
                                <p class="card-text">{{ `quiz/showQuestion/${formName.link}` }}</p>
                            </a>
                        </div>

                        <a :href="`quiz/showResults/${formName.id}`" class="btn btn-warning">Show Results</a>
                        <button @click="deleteQuestion(formName.id)" class="btn btn-danger m-1">Delete</button>
                        <button :href="`quiz/editQuestion/${formName.user_id}`" class="btn btn-primary m-1"
                            disabled>Edit</button>

                    </div>
                </div>
            </div>
        </div>
    </div>




</template>
