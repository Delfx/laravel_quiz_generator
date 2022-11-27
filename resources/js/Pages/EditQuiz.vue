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

}


</script>


<template>


    <Navbar :login="$page.props.auth.user">
    </Navbar>

    <h5 v-for="question in $page['props']['questions']">

        {{question.name}}
        <!-- {{$page['props']['questions']}} -->
    </h5>

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
                <div class=" mt-4 " v-for="(question, index) in $page['props']['questions']" :key="index">
                    <div class="form-group col-md-12 m-2">
                        <label class="m-1">Question name</label>
                        <input v-model="question.name" :name="`questions[${index}][question]`" type="text"
                            class="form-control" placeholder="Question name">
                        <div v-if="errors[`questions.${index}.question`]" class="tw-mt-3 alert alert-danger"
                            role="alert">
                            {{ errors[`questions.${index}.question`] }}
                        </div>
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


