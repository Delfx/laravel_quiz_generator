<script setup>
import { reactive, ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia'
import Navbar from '@/Components/Navbar.vue';


const props = defineProps({

    allQuizForms: Array,
    allQuestions: Array

})

// let correctAnswersNumbers = []
// let userAnswersNumbers = []



function countAnswersNumber(index) {

    const matches = [];

    props.allQuestions[index].user_answers.forEach(element => {
        if (element.user_answer === element.correct_answer) {
            matches.push(element.correct_answer);
        }
    })

    return matches.length;
}


function deleteAnswer(resultId) {

    Inertia.delete(route("deleteAnswer", resultId));

}

</script>



<template>

    <Navbar :login="$page.props.auth.user">
    </Navbar>

    <div class="container">

        <div v-if="(props.allQuestions.length == 0)">
            <h3 class="mt-5">
                Sorry, nobody solved your Quiz
            </h3>
        </div>

        <div v-for="(item, index) in props.allQuestions" :key="item.id">

            <div class="card col-md-8 d-flex mx-auto mt-4">
                <div class="card-body">
                    <h5 class="m-0" style="font-size: 0.8rem;">User:</h5>
                    <h3 class="card-title">{{ item.name }}</h3>
                    <p class="card-text">All questions: {{ item.user_answers.length }}</p>
                    <p class="card-text">Correct Answers: {{ countAnswersNumber(index) }}</p>
                    <a :href="route('showQuizAnswerDetails', { userId: item.user.id, quizId: item.quiz_form_id })"
                        class="btn btn-primary">Detail Result</a>
                    <button @click="deleteAnswer(item.id)" class="btn btn-danger m-2">Delete</button>
                </div>
            </div>
        </div>
    </div>




</template>
