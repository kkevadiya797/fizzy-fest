<template>
    <div v-if="option.choice" class="col-12 col-md-6 px-2 mb-2">
        <div 
            class="px-2 py-1 d-flex align-items-center fz-option"
            :class="[colorClasses[index],{'fz-option-correct' : option.is_choice_right}]"
            >
            <i 
                class="fas card-icon mr-1"
                :class="iconClasses[index]"
                ></i>
            <p class="m-0 ml-2">
                {{ option.choice }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        option: {
            type: Object,
            required: true
        },
        index: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            // iconClasses: ['fa-heart', 'fa-circle', 'fa-square', 'fa-star'],
            // emojis
            // iconClasses: ['fa-grin-wink', 'fa-grin-alt', 'fa-grin-stars', 'fa-grin-tears'],
            // education
            iconClasses: ['fa-apple-alt', 'fa-shapes', 'fa-brain', 'fa-book'],
            colorClasses: ['fz-option-danger', 'fz-option-info', 'fz-option-success', 'fz-option-warning'],
        }
    }
}
</script>

<style scoped>
/* fizzy option */
.card .fz-option {
    border-width: 0;
    border-left-width: 5px;
    border-style: solid;
}

.card .fz-option-primary {
    border-color: #3a3ab1;
}

.card .fz-option-danger {
    border-color: #fc544b;
}

.card .fz-option-warning {
    border-color: #ffa426;
}

.card .fz-option-info {
    border-color: #3abaf4;
}

.card .fz-option-success {
    border-color: #1eca91;
}

.card .card-icon {
    font-size: 1rem;
}

/* option icon */
.fz-option-primary i {
    color: #3a3ab1;
}

.fz-option-danger i {
    color: #fc544b;
}

.fz-option-warning i {
    color: #ffa426;
}

.fz-option-info i {
    color: #3abaf4;
}

.fz-option-success i {
    color: #1eca91;
}

/* correct option */
.card .fz-option-correct {
    background-color: rgba(99, 237, 122, 0.16);
}

.card .fz-option-correct p {
    font-weight: bold!important;
}
</style>

