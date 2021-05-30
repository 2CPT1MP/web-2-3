<?php
require_once('header.view.php');
require_once('../models/photo.model.php');

class PhotosView {
    public static function render(array $photos): string {
        $html = HeaderView::render('Альбом');
        $html .= "
        <main>
			<div class='flex-container' id='gallery-div'>
				<div class='flex-item'>
					<img src=\"{$photos[0]->getPath()}\" class='enlarged' alt='Not loaded'>
					<p class='image-title'> </p>
					<button id='next-img'>Следующий</button>
					<button id='prev-img'>Предыдущий</button>
				</div>
			</div>
			<section class='flex-container card' id='img-container'>
        ";

        $i = 0;
        foreach ($photos as $photo) {
            $html .= "
                <div id=$i class='flex-item'>
                    <img src={$photo->getPath()} title=\"{$photo->getTitle()}\" alt=''> 
                    <p class='image-title'>{$photo->getTitle()}</p>
                </div>
            ";
            $i++;
        }
        return $html . '</section></main>';
    }
}