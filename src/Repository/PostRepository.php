<?php

namespace App\Repository;

use App\Entity\Post;

class PostRepository
{
    public function find(int $id): Post
    {
        $post = new Post
        (
            "Mathieu.D",
            "Ceci est mon article", 
            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt itaque necessitatibus vel nostrum tenetur provident pariatur, doloribus officia at quisquam tempore.", 
            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt itaque necessitatibus vel nostrum tenetur provident pariatur, odit tempore facilis nam totam incidunt harum, distinctio qui architecto, neque a recusandae aut. Beatae distinctio autem commodi, atque nulla, omnis quod quibusdam odit iure repellendus iusto reprehenderit molestiae doloribus officia at quisquam tempore.

            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt itaque necessitatibus vel nostrum tenetur provident pariatur, odit tempore facilis nam totam incidunt harum, distinctio qui architecto, neque a recusandae aut. Beatae distinctio autem commodi, atque nulla, omnis quod quibusdam odit iure repellendus iusto reprehenderit molestiae doloribus officia at quisquam tempore."
        );

        return $post;
    }
}