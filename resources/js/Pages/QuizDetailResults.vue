<script setup>
import Navbar from '@/Components/Navbar.vue';
import { Inertia } from '@inertiajs/inertia'
import { ref } from 'vue';


const props = defineProps({
    questionForm: Array,
    answers: Array,
    previous_url: String

})


function countAnswersNumber(index) {



    const matches = [];

    // matches.push(props.questionForm[0].user_answers[index].correct_answer);

    props.questionForm[0].user_answers.forEach(element => {
        if (element.user_answer === element.correct_answer) {
            matches.push(element.correct_answer);
        }
    })

    return matches.length;

}

</script>

<template>

    <Navbar :login="$page.props.auth.user">
    </Navbar>

    <div class="container">

        <div class="d-flex flex-column align-items-center mt-3">
            <form>
                <div class="text-center">
                    <h1>
                        {{ questionForm[0].quiz_form.name }}
                    </h1>
                    <div class="text-center mt-3">
                        <h6 class="m-0">
                            User name:
                        </h6>
                        <h1>
                            {{ questionForm[0].name }}
                        </h1>
                    </div>

                </div>

                <div v-for="(userAnswers, index) in props.questionForm[0].user_answers" :key="index">

                    <div class="card mt-3" style="width: 25rem;">
                        <div class="card-body">

                            <h4 class="">
                                {{ userAnswers.name }}
                            </h4>

                            <div v-for="(answer, answerIndex) in props.answers[index]['answer']" :key="answerIndex">

                                <div class="form-check">
                                    <input v-model="userAnswers.user_answer" :class="'form-check-input'"
                                        :value="answerIndex" type="radio" :name="`flexRadioDefault${index}`"
                                        id="flexRadioDefault" disabled>

                                    <label
                                        :class="[answerIndex == userAnswers.correct_answer ? 'text-primary' : 'text-danger']"
                                        for="flexRadioDefault">
                                        {{ answer.name }} {{ answerIndex == userAnswers.correct_answer ?
                                                '-- Correct Answer'
                                                : ''
                                        }}

                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div :class="props.questionForm[0].user_answers.length == countAnswersNumber() ? 'alert alert-success mt-3'
                         : (countAnswersNumber() == 0) ? 'alert alert-danger mt-3' : 'alert alert-warning mt-3'" role="alert">

                        {{
                        props.questionForm[0].user_answers.length == countAnswersNumber() ? 'Congratulations you did not make any mistake.'
                         : (countAnswersNumber() == 0) ? 'Sorry you did not answer any Question correctly' : 'You made ' +
                         (props.questionForm[0].user_answers.length - countAnswersNumber()) + ' mistake(s)'
                        }}

                </div>

            </form>

            <div class="form-group mt-3">
                <a :href="props.previous_url" class="btn btn-primary btn-lg">Back</a>
                <!-- <button @click="back" type="button" class="btn btn-primary btn-lg">Back</button> -->
            </div>


        </div>
    </div>

</template>
