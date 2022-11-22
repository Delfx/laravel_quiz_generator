<script setup>
import { reactive, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'


defineProps({
    errors: Object
})


const questions = ref([
    {
        question: 'Are you a good boy?',
        answerTrue: '0',
        answers: [
            'YES',
            'NO',
            'Maybe'
        ]

    },
    {
        question: 'Are you a good boy?',
        answerTrue: '0',
        answers: [
            'YES',
            'NO',
            'Maybe'
        ]

    }
])

function addQuestions() {
    questions.value.push({
        question: '',
        answerTrue: '',
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
        questions: questions.value
    }

    Inertia.post('/post', data)
    //   alert(JSON.stringify(data, null, 2))

}


</script>


<template>
    <div class="container d-flex">
        <form class="mx-auto col-6">
            <h1>Question Maker</h1>
            <div class="question-maker">
                <div class=" mt-4 " v-for="(question, index) in questions" :key="index">
                    <div class="form-group col-md-12 m-2">
                        <label class="m-1">Question name</label>
                        <input v-model="question.question" :name="`questions[${index}][question]`" type="text"
                            class="form-control" placeholder="Question name">
                    </div>

                    <div v-if="errors.question">{{ errors.question }}</div>

                    <div v-for="(answer, indexAnswer) in questions[index].answers" :key="indexAnswer">
                        <div class="form-group col-md-12 m-2">
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input :value="indexAnswer" class="form-check-input mt-0" type="radio"
                                        v-model="questions[index].answerTrue"
                                        aria-label="Radio button for following text input">
                                </div>
                                <input v-model="questions[index].answers[indexAnswer]"
                                    :name="`questions[${index}][answers][${indexAnswer}]`" type="text"
                                    class="form-control" aria-label="Answer">
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



<style>

</style>
