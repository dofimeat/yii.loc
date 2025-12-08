<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="https://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row mb-5">
            <div class="col-12">
                <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#imageCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#imageCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#imageCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    
                    <div class="carousel-inner rounded-3" style="box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
                        <div class="carousel-item active" data-bs-interval="3000">
                            <?php
                            $image1 = 'slider1.png';
                            if (file_exists(Yii::getAlias('@webroot/img/' . $image1))): ?>
                                <img src="<?= Yii::getAlias('@web/img/' . $image1) ?>" class="d-block w-100" alt="Technology" style="height: 400px; object-fit: cover;">
                            <?php else: ?>
                                <div class="d-block w-100 bg-primary" style="height: 400px; display: flex; align-items: center; justify-content: center;">
                                    <div class="text-white text-center">
                                        <h4>Technology Image</h4>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-4">
                                <h5 class="text-white">Технологии будущего</h5>
                                <p>Исследуйте последние инновации в мире технологий и программирования.</p>
                            </div>
                        </div>
                        
                        <div class="carousel-item" data-bs-interval="3000">
                            <?php
                            $image2 = 'slider2.png';
                            if (file_exists(Yii::getAlias('@webroot/img/' . $image2))): ?>
                                <img src="<?= Yii::getAlias('@web/img/' . $image2) ?>" class="d-block w-100" alt="Design" style="height: 400px; object-fit: cover;">
                            <?php else: ?>
                                <div class="d-block w-100" style="height: 400px; background: linear-gradient(135deg, #1dbfc5, #30b8bd); display: flex; align-items: center; justify-content: center;">
                                    <div class="text-white text-center">
                                        <h4>Design Image</h4>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-4">
                                <h5 class="text-white">Современный дизайн</h5>
                                <p>Тренды в UI/UX дизайне для создания потрясающих пользовательских интерфейсов.</p>
                            </div>
                        </div>
                        
                        <div class="carousel-item" data-bs-interval="3000">
                            <?php
                            $image3 = 'slider3.png';
                            if (file_exists(Yii::getAlias('@webroot/img/' . $image3))): ?>
                                <img src="<?= Yii::getAlias('@web/img/' . $image3) ?>" class="d-block w-100" alt="Business" style="height: 400px; object-fit: cover;">
                            <?php else: ?>
                                <div class="d-block w-100" style="height: 400px; background: linear-gradient(135deg, #259a9f, #1dbfc5); display: flex; align-items: center; justify-content: center;">
                                    <div class="text-white text-center">
                                        <h4>Business Image</h4>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-4">
                                <h5 class="text-white">Бизнес и стартапы</h5>
                                <p>Стратегии успеха и управления для современных предпринимателей.</p>
                            </div>
                        </div>
                    </div>
                    
                    <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title h4 fw-bold">Технологии</h2>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                            ex ea commodo consequat.
                        </p>
                        <a class="btn btn-outline-primary" href="">
                            Читать статьи <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title h4 fw-bold">Дизайн</h2>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                            ex ea commodo consequat.
                        </p>
                        <a class="btn btn-outline-primary" href="">
                            Изучать дизайн <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title h4 fw-bold">Бизнес</h2>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                            ex ea commodo consequat.
                        </p>
                        <a class="btn btn-outline-primary" href="">
                            Развиваться <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
