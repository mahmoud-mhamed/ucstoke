@section('css')
    <style>
        /*start contactIconLeft*/
        aside.contactIconLeft{
            position: fixed;
            left: 0;
            top: 35%;
            /*display: none;*/
        }
        aside.contactIconLeft a{
            text-decoration: none;
            color: white;
            display: block;
            width: 50px;
            height: 50px;
            text-align: center;
            transition: all .2s ease-in-out;
            opacity: .8;
            cursor: pointer;

        }
        @media (max-width: 576px){
            aside.contactIconLeft{
                display: none;
            }
        }
        aside.contactIconLeft a:hover {
            width: 60px;
            opacity: 1;
        }
        aside.contactIconLeft a i{
            margin-top: 5px;
        }
        aside.contactIconLeft a:nth-child(1) {
            background: #02783A;
        }
        aside.contactIconLeft a:nth-child(1):hover {
            background: #085f55;
        }
        aside.contactIconLeft a:nth-child(2) {
            background: #3b5998;
        }
        aside.contactIconLeft a:nth-child(2):hover {
            background: #0e2e72;
        }
        aside.contactIconLeft a:nth-child(3) {
            background: #085f55;
        }
        aside.contactIconLeft a:nth-child(3):hover {
            background: #02783A;
        }
        aside.contactIconLeft a:nth-child(4) {
            background: #0e2e72;
        }
        aside.contactIconLeft a:nth-child(4):hover {
            background: #3b5998;
        }
        /*end contactIconLeft*/
        /*start contactIconBottom*/
        .contactIconBottom{
            position:fixed;
            bottom: 0;
            width: 100vw;
            background: #a5dc86;
            display: none;
            z-index: 1000;
        }
        aside.contactIconBottom a{
            text-decoration: none;
            color: white;
            display: block;
            height: 50px;
            transition: all .2s ease-in-out;
            cursor: pointer;
        }
        aside.contactIconBottom a i{
            margin-top: 5px;
        }
        aside.contactIconBottom a:nth-child(1) {
            background: #02783A;
        }
        aside.contactIconBottom a:nth-child(1):hover {
            background: #085f55;
        }
        aside.contactIconBottom a:nth-child(2) {
            background: #3b5998;
        }
        aside.contactIconBottom a:nth-child(2):hover {
            background: #0e2e72;
        }
        aside.contactIconBottom a:nth-child(3) {
            background: #085f55;
        }
        aside.contactIconBottom a:nth-child(3):hover {
            background: #02783A;
        }
        aside.contactIconBottom a:nth-child(4) {
            background: #0e2e72;
        }
        aside.contactIconBottom a:nth-child(4):hover {
            background: #3b5998;
        }
        @media (max-width: 576px){
            body{
                padding-bottom: 50px;
            }
            aside.contactIconBottom {
                display: flex;
            }
        }
        @media (min-width: 576px){
            aside.contactIconBottom{
                display: none;
            }
        }
        /*end contactIconBottom*/
    </style>
@append
@section('content')
    <aside class="contactIconLeft">
        <a class="py-1 tooltips" data-placement="right" title="التواصل عن طريق الواتس" target="_blank" href="https://api.whatsapp.com/send?phone=01018030420">
            <i class="fab fa-whatsapp fa-2x"></i>
        </a>
        <a class="py-1 tooltips" data-placement="right" title="التواصل عن طريق الفيس بوك" target="_blank" href="https://www.facebook.com/Ultimate-Code-109446277305612/">
            <i class="fab fa-facebook-square fa-2x"></i>
        </a>
        <a class="py-1 tooltips" data-placement="right" title="الإتصال بالدعم الفنى" target='_blank' href="tel:01018030420">
            <i class="fas fa-mobile-alt fa-2x"></i>
        </a>
        <a class="py-2 text-white tooltips" data-placement="right" title="زيارة موقع الشركة" target="_blank" href="https://www.facebook.com/Ultimate-Code-109446277305612/">
            <img src="{{asset('img/icon.ico')}}" style="width: 37px;height: 37px" alt="">
        </a>
    </aside>
    <aside class="contactIconBottom row no-gutters text-center">
        <a class="py-1 col tooltips" data-placement="top" title="التواصل عن طريق الواتس" target="_blank" href="https://api.whatsapp.com/send?phone=01018030420">
            <i class="fab fa-whatsapp fa-2x"></i>
        </a>
        <a class="py-1 col tooltips" data-placement="top" title="التواصل عن طريق الفيس بوك" target="_blank" href="https://www.facebook.com/Ultimate-Code-109446277305612/">
            <i class="fab fa-facebook-square fa-2x"></i>
        </a>
        <a class="py-1 col tooltips" data-placement="top" title="الإتصال بالدعم الفنى" href="tel:01018030420">
            <i class="fas fa-mobile-alt fa-2x"></i>
        </a>
        <a class="py-2 col tooltips" data-placement="top" title="زيارة موقع الشركة" target="_blank" href="https://www.facebook.com/Ultimate-Code-109446277305612/">
            <img src="{{asset('img/icon.ico')}}" style="width: 37px;height: 37px" alt="">
        </a>
    </aside>
@append


