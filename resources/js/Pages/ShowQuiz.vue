-- Active: 1669226883728@@127.0.0.1@3306@laravel
<script setup>
import Navbar from '@/Components/Navbar.vue';
import { Inertia } from '@inertiajs/inertia'
import { ref } from 'vue';


const props = defineProps({

    formName: String,
    questionsName: Array,
    answers: Array,

})

const answers = ref(

    {
        formName: props.formName,
        questionsName: props.questionsName,
        answers: props.answers,

    }
)

answers.value.questionsName.forEach(answer => {
    answer.correct_answer = ''

});

function submit() {
    const data = {
        answerForm: answers.value,
    }


    Inertia.post('/quiz/addAnswer', data.answerForm)
    // console.log(data.value.questionsName[0]);
}


</script>

<template>

    <Navbar :login="$page.props.auth.user">
    </Navbar>
    <div class="container">

        <div class="d-flex flex-column align-items-center mt-3">
            <form>
                <div class="">
                    <h1>
                        {{ answers.formName }}
                    </h1>
                </div>

                <div v-for="(questionName, index) in answers.questionsName" :key="index">

                    <h1>
                        {{ questionName.correct_answer }}
                    </h1>

                    <div class="card mt-3" style="width: 25rem;">
                        <div class="card-body">

                            <h4 class="">
                                {{ questionName.name }}
                            </h4>

                            <div v-for="(answer, answerIndex) in answers.answers[index]['answer']" :key="answerIndex">
                                <div class="form-check">
                                    <input v-model="questionName.correct_answer" class="form-check-input"
                                        :value="answerIndex" type="radio" :name="`flexRadioDefault${index}`"
                                        id="flexRadioDefault">
                                    <label class="form-check-label" for="flexRadioDefault">
                                        {{ answer.name }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mt-5">
                    <button @click="submit" type="button" class="btn btn-primary btn-lg">Submit</button>
                </div>
            </form>


        </div>
    </div>

</template>
