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

    let correctAnswersNumbers = []
    let userAnswersNumbers = []
    // return 'test'

    // props.allQuestions.forEach(element => {
    //     // correctAnswersNumber = element.user_answers.correct_answer
    //     array.forEach(element => {

    //     });
    //     correctAnswersNumbers.push(element.user_answers.correct_answer)
    //     userAnswersNumbers.push(element.user_answers[index].user_answer)
    // });

    props.allQuestions[index].user_answers.forEach(element => {
        correctAnswersNumbers.push(element.correct_answer)
        userAnswersNumbers.push(element.user_answer)
    })


    return userAnswersNumbers 

    // correctAnswersNumbers.push(props.allQuestions[index])



    // correctAnswersNumbers = []
    // userAnswersNumbers = []
    // return correctAnswersNumbers;



}





// onMounted(() => {
//     countAnswersNumber();
// })




</script>



<template>

    <Navbar :login="$page.props.auth.user">
    </Navbar>

    <div class="container">

        <div v-for="(item, index) in props.allQuestions" :key="item.id">

            {{ countAnswersNumber(index) }}

            <!-- {{ allQuestions[index]['user_answers'].slice(Math.max(allQuestions[index]['user_answers'].length - 5, 1)) }} -->

            <div>
                {{ userAnswersNumbers }}
            </div>

            <!-- <div>
                {{ index == 0 ?  correctAnswersNumbers : correctAnswersNumbers.slice(Math.max(correctAnswersNumbers.length - item.user_answers.length, 1))}}
            </div>


            <div>
                {{ index == 0 ?  userAnswersNumbers : userAnswersNumbers.slice(Math.max(userAnswersNumbers.length - item.user_answers.length, 1))}}
            </div> -->
            <!-- <div>
                {{ userAnswersNumbers.slice(Math.max(userAnswersNumbers.length - item.user_answers.length, 1)) }}
            </div> -->

            <div class="card col-md-8 d-flex mx-auto mt-4">
                <div class="card-body">
                    <h5 class="m-0" style="font-size: 0.8rem;">User:</h5>
                    <h3 class="card-title">{{ item.name }}</h3>
                    <p class="card-text">All questions: {{ item.user_answers.length }}</p>
                    <p class="card-text">Correct Answers: {{ item.user_answers.length }}</p>
                    <a href="#" class="btn btn-primary">Detail Result</a>
                </div>
            </div>
        </div>
    </div>




</template>
