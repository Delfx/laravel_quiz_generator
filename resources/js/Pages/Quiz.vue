<script setup>
import { reactive, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Navbar from '@/Components/Navbar.vue';

defineProps({
    errors: Object,
})

const questionFormName = ref('Dogs');

const questions = ref([
    {
        question: 'Are you a good boy?',
        trueAnswer: '0',
        answers: [
            'YES',
            'NO',
            'Maybe'
        ]

    },
    {
        question: 'Labrador best human friend?',
        trueAnswer: '0',
        answers: [
            'yes',
            'NO',
            'Maybe'
        ]

    }
])

function addQuestions() {

    questions.value.push({
        question: '',
        trueAnswer: '',
        answers: ['',
            '',
            ''
        ],
    })
}

function addAnswer(index) {
    questions.value[index].answers.push('')
}

function removeQuestions(index) {
    questions.value.splice(index, 1)
}

function submit() {
    const data = {
        questionsFormName: questionFormName.value,
        questions: questions.value
    }
    Inertia.post('/quiz/addQuestion', data, {
        preserveState: true,
        preserveScroll: true,
    })



    // Inertia.visit('/post123', {
    //     method: 'post',
    //     data: data,
    //     replace: false,
    //     preserveState: false,
    //     preserveScroll: false,
    //     replace: false
    // })

    // this.$inertia.post('/post123', data)
}


</script>


<template>


    <Navbar :login="$page.props.auth.user">
    </Navbar>


    <div class="container d-flex">
        <form class="mx-auto col-6">

            <h1>Question Maker</h1>

            <div class="form-group col-md-12 m-2">
                <label class="m-1">Question Form name</label>
                <input v-model="questionFormName" :name="`questionFormName.value`" type="text" class="form-control"
                    placeholder="Question Form name">
                <div v-if="errors.questionsFormName" class="tw-mt-3 alert alert-danger" role="alert">
                    {{ errors.questionsFormName }}
                </div>
            </div>

            <div class="question-maker">
                <div class=" mt-4 " v-for="(question, index) in questions" :key="index">
                    <div class="form-group col-md-12 m-2">
                        <label class="m-1">Question name</label>
                        <input v-model="question.question" :name="`questions[${index}][question]`" type="text"
                            class="form-control" placeholder="Question name">
                        <div v-if="errors[`questions.${index}.question`]" class="tw-mt-3 alert alert-danger"
                            role="alert">
                            {{ errors[`questions.${index}.question`] }}
                        </div>
                    </div>

                    <div v-for="(answer, indexAnswer) in questions[index].answers" :key="indexAnswer">
                        <div class="form-group col-md-12 m-2">
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input :value="indexAnswer" class="form-check-input mt-0" type="radio"
                                        v-model="questions[index].trueAnswer"
                                        aria-label="Radio button for following text input">
                                </div>
                                <input v-model="questions[index].answers[indexAnswer]"
                                    :name="`questions[${index}][answers][${indexAnswer}]`" type="text"
                                    class="form-control" aria-label="Answer" placeholder="Answer Form name">
                            </div>
                            <div v-if="errors[`questions.${index}.answers.${indexAnswer}`]"
                                class="tw-mt-3 alert alert-danger" role="alert">
                                {{ errors[`questions.${index}.answers.${indexAnswer}`] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button @click="removeQuestions(index)" type="button" class="btn btn-danger btn-sm">Remove
                            question</button>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button @click="addQuestions" type="button" class="btn btn-secondary">Add new question</button>
            </div>

            <hr>

            <div class="form-group">
                <button @click="submit" type="button" class="btn btn-primary">Submit</button>
            </div>

        </form>

    </div>
</template>


