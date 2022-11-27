<script setup>
import Navbar from '@/Components/Navbar.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    allQuestionsFormsName: Array,
    allQuestions: Array
})

</script>


<template>

    <Navbar :login="$page.props.auth.user">
    </Navbar>


    <div class="container mt-5">

        <Link href="/quiz" class="btn btn-success mb-4">Create quiz</Link>

        <div v-if="$page.props.auth.user">
            <div v-for="(formName, index) in allQuestionsFormsName" :key="index">
                <div class="card mt-3 col-md-8 d-flex mx-auto">
                    <div class="card-header">
                        {{ formName.name }}
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">{{ formName.name }}</h3>
                        <p class="card-text m-0 p-0">Questions in form: {{ allQuestions[index].questions.length }}</p>
                        <a class="card-text" :href="`quiz/showQuestion/${formName.link}`">
                            <p class="card-text">Link:  {{`quiz/showQuestion/${formName.link}`}}</p>
                        </a>
                        <a :href="`quiz/editQuestion/${formName.id}`" class="btn btn-primary mt-3">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>




</template>
