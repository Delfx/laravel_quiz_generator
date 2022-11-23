<script setup>
import { reactive, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    isFilled: String
})

const questionFormName = ref([]);

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
        questionsFormName: questionFormName.value,
        questions: questions.value
    }
    // Inertia.post('/post123', data, {
    //     preserveState: true,
    //     preserveScroll: true,
    // })

    Inertia.visit('/post123', {
        method: 'post',
        data: data,
        replace: false,
        preserveState: false,
        preserveScroll: false,
    })

    // this.$inertia.post('/post123', data)
}


</script>


<template>

    <div class="tw-pt-4 tw-pb-1 tw-border-t tw-border-gray-200">
        <div class="tw-mt-3 tw-space-y-1">
            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                Log Out
            </ResponsiveNavLink>
        </div>
    </div>


    <div class="container d-flex">
        <form class="mx-auto col-6">
            <h1>Question Maker</h1>
            <div class="form-group col-md-12 m-2">
                <label class="m-1">Question Form name</label>
                <input v-model="questionFormName" :name="`questionFormName.value`" type="text" class="form-control"
                    placeholder="Question Form name">
            </div>
            <div class="question-maker">
                <div class=" mt-4 " v-for="(question, index) in questions" :key="index">
                    <div class="form-group col-md-12 m-2">
                        <label class="m-1">Question name</label>
                        <input v-model="question.question" :name="`questions[${index}][question]`" type="text"
                            class="form-control" placeholder="Question name">
                    </div>

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

            <h2 v-if="isFilled">{{ isFilled }}</h2>

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


