<script setup>
import { dateToStr } from '@/utils/helper'
import { baseURL } from '@/api'

import CreateCommentForm from '@/components/CreateCommentForm.vue'
import Comments from '@/components/Comments.vue'
</script>

<template>
    <Popup ref='popup' />
    <div class='container'>
        <h1 class='title'>{{ this.post.title }}</h1>
        <p class='descr' v-html="this.post.description"></p>
        <br>
        <div class='stats'>
            <div>
                <img src='@/assets/icons/view.svg' alt='views'>
                <p>{{ post.views }}</p>
            </div>
            <div class='like-wrapper' @click='like'>
                <p v-if='this.alreadyLiked' class='liked'>Вам нравится эта новость!</p>
                <p>{{ post.likes }}</p>
                <img src='@/assets/icons/like.svg' alt='like'>
            </div>
        </div>
        <img class='thumbnail' :src='this.getThumbnailPath(this.post.thumbnail)' alt='thumbnail'>
        <p class='content' v-html="this.post.content"></p>
        <div class='delete' @click='deleteNews'>
            <img src='@/assets/icons/delete.svg' alt='delete'>
            <p>Удалить эту новость</p>
        </div>
        <div class='details'>
            <p class='date'>{{ dateToStr(this.post.created_at) }}</p>
            <p class='author'>Автор: {{ this.post.author }}</p>
        </div>
        <hr>
        <CreateCommentForm :postId='this.post.id' />
        <Comments :comments='this.post.comments' />
    </div>
</template>

<script>
import api from '@/api'
import Popup from '@/components/Popup.vue'

export default {
    data() {
        return {
            post: {
                id: null,
                title: '',
                description: '',
                content: '',
                created_at: '',
                thumbnail: '',
                author: '',
                comments: [],
                views: 0,
                likes: 0
            },
            alreadyLiked: false
        }
    },
    created() {
        console.log('Fetching post...')
        this.fetchPost()
    },
    methods: {
        async fetchPost() {
            try {
                const response = await api.get(`/api/news/${this.$route.params.id}`)
                const { success, news, error } = response.data
                if (success)
                    this.post = news
                else
                    throw error
            } catch (err) {
                console.error('Ошибка при получении новости:', err)
            }
        },
        async like() {
            try {
                let request = `/api/news/${this.post.id}/`
                if (this.alreadyLiked)
                    request += 'unlike'
                else
                    request += 'like'
                const response = await api.put(request)
                const { success, likes, error } = response.data
                if (success)
                    this.post.likes = likes
                else
                    throw error

                this.alreadyLiked = !this.alreadyLiked
            } catch (err) {
                console.error('Ошибка при получении новости:', err)
            }
        },
        getThumbnailPath(thumbnail) {
            if (thumbnail)
                return baseURL + '/postimages/' + thumbnail
            return '../src/assets/images/thumbnail-default.webp'
        },
        async deleteNews() {
            try {
                const response = await api.delete(`/api/news/${this.post.id}`)
                const { success, error } = response.data
                if (!success)
                    throw error

                    this.$router.push('/')
            } catch (err) {
                console.error('Ошибка при удалении новости:', err)
                this.$refs.popup.openPopup(false, 'Произошла ошибка')
            }
        }
    }
}
</script>

<style scoped>
.container {
    margin: 0 auto;
    padding: 0 10%;
    margin-bottom: 2.5rem;
}

.title {
    font-size: 2rem;
    text-transform: uppercase;
    font-weight: bold;
}

.descr {
    font-size: 1.3rem;
}

.like-wrapper:hover {
    cursor: pointer;
}

.liked {
    color: green;
}

.thumbnail {
    width: 100%;
    max-height: 500px;
    object-fit: cover;
    margin: 1.5rem 0;
}

.content {
    font-size: 1rem;
    margin-bottom: 30px;
}

.delete {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    align-items: center;
    color: red;
    text-transform: uppercase;
    margin-bottom: 15px;
}


.delete:hover {
    cursor: pointer;
    text-decoration: underline;
}

.delete img {
    display: none;
    margin-right: 5px;
}

.delete:hover img {
    display: block;
}

.delete img {
    height: 1.2rem;
}

.delete p {
    font-weight: bold;
}

.details {
    display: flex;
    width: 100%;
    flex-direction: row;
    justify-content: space-between;
    font-size: 1.3rem;
}
</style>