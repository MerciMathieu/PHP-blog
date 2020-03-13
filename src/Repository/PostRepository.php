<?php

namespace App\Repository;

use App\Entity\Post;

class PostRepository
{

    public function findAll(): array
    {
        $posts = [];

        $post1 = new Post
        (
            "Auteur 1",
            "Article 1", 
            "Intro de l'article 1", 
            "Contenu de l'article 1 !"
        );
        $post1->setImageUrl('http://placekitten.com/g/200/200');

        $post2 = new Post
        (
            "Auteur 2",
            "Article 2", 
            "Intro de l'article 2", 
            "Contenu de l'article 2"
        );

        $posts = array($post1, $post2);

        return $posts;

    }

    public function find(int $id): Post
    {
        $post = new Post
        (
            "Mathieu.D",
            "Ceci est mon article dynamique", 
            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt itaque necessitatibus vel nostrum tenetur provident pariatur, doloribus officia at quisquam tempore.", 
            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt itaque necessitatibus vel nostrum tenetur provident pariatur, odit tempore facilis nam totam incidunt harum, distinctio qui architecto, neque a recusandae aut. Beatae distinctio autem commodi, atque nulla, omnis quod quibusdam odit iure repellendus iusto reprehenderit molestiae doloribus officia at quisquam tempore.

            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt itaque necessitatibus vel nostrum tenetur provident pariatur, odit tempore facilis nam totam incidunt harum, distinctio qui architecto, neque a recusandae aut. Beatae distinctio autem commodi, atque nulla, omnis quod quibusdam odit iure repellendus iusto reprehenderit molestiae doloribus officia at quisquam tempore."
        );
        $post->setImageUrl("http://placekitten.com/g/400/400");

        return $post;
    }
}