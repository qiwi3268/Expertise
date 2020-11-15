<?php if (!is_null($text = \core\Classes\Session::getErrorMessage())): ?>
    <div class="static-error"
         style="visibility: visible; opacity: 1; transition: 0.3s all"
    >
        <div onclick="{
                let static_error = document.querySelector('.static-error');
                static_error.style.opacity = 0;
                static_error.style.visibility = 'hidden';
                setTimeout(() => static_error.remove(), 300);
            }"
            style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 20;
                background-color: rgba(0, 0, 0, .5);"
        >
        </div>
        <div style="
                opacity: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                position: fixed;
                top: 45%;
                left: 50%;
                z-index: 50;
                width: 100%;
                max-width: 500px;
                border-radius: 3px;
                box-shadow: 0 3px 10px -.5px rgba(0, 0, 0, .2);
                background-color: #ffffff;
                transform: translate(-50%, -50%);
                transition: 0.3s all;
                font-family: 'Roboto Condensed', sans-serif;
                color: #343a40;"
        >
            <div class="fas fa-times"
                 onclick="{
                     let static_error = document.querySelector('.static-error');
                     static_error.style.opacity = 0;
                     static_error.style.visibility = 'hidden';
                     setTimeout(() => static_error.remove(), 300);
                 }"
                 
                 onmouseover="this.style.opacity='1';"
                 
                 onmouseout="this.style.opacity='.5';"
                 
                 style="
                   border-radius: 3px;
                   color: #fff;
                   opacity: .5;
                   transition: opacity .3s;
                   cursor: pointer;
                   font-size: 34px;
                   padding: 5px 10px 5px 10px;
                   position: absolute;
                   right: -50px;"
            >
            </div>
            <div style="padding: 10px 15px; border-bottom: 1px solid #cccccc;">
                <i class="fas fa-exclamation" style="margin: 0px 5px 0px 0px; font-size: 24px; color: #bd4949;"></i>
                <span style="font-weight: 700; font-size: 18px;">Ошибка</span>
            </div>
            <span style="padding: 10px 15px"><?= $text ?></span>
        </div>
    </div>
<?php endif; ?>

