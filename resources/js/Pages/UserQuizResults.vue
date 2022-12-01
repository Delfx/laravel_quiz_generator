<script setup>
import { reactive, ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia'
import Navbar from '@/Components/Navbar.vue';



const props = defineProps({
    allQuestions: Array
})


function countAnswersNumber(index) {

    const matches = [];

    props.allQuestions[index].user_answers.forEach(element => {
        if (element.user_answer === element.correct_answer) {
            matches.push(element.correct_answer);
        }
    })

    return matches.length
}

</script>



<template>

    <Navbar :login="$page.props.auth.user">
    </Navbar>

    <div class="container">

        <div v-if="(props.allQuestions.length == 0)">
            <h3 class="mt-5">
                Sorry you do not solve any Quiz
            </h3>
        </div>

        <div v-for="(item, index) in allQuestions" :key="item.id">


            <div class="card col-md-8 d-flex mx-auto mt-4">
                <div class="card-body">
                    <h5 class="m-0" style="font-size: 0.8rem;">Quiz:</h5>
                    <h3 class="card-title">{{ item.quiz_form.name }}</h3>
                    <p class="card-text m-0">All Questions: {{ item.user_answers.length }}</p>
                    <p class="card-text">Correct Answers: {{ countAnswersNumber(index) }}</p>
                    <a :href="route('showQuizAnswerDetails', { userId: item.user_id, quizId: item.quiz_form.id })"
                        class="btn btn-primary m-2">Detail Result</a>
                </div>
            </div>
        </div>
    </div>

</template>
