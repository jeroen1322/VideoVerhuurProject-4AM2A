<div class="panel panel-default">
  <div class="panel-body">

    <div>
        <div>
        <h2> Contactformulier:</h2>
            <div class="inner contact">
                <div class="contact-form">
                    <form id="contact-us" method="post" action="#">
                        <div class="col-xs-6 wow animated slideInLeft" data-wow-delay=".5s">
                            <input type="text" name="name" id="name" required="required" class="form" placeholder="Naam" />
                            <input type="email" name="mail" id="mail" required="required" class="form" placeholder="Email" />
                            <input type="text" name="subject" id="subject" required="required" class="form" placeholder="Onderwerp" />
                        </div>
                        <div class="col-xs-6 wow animated slideInRight" data-wow-delay=".5s">
                            <textarea name="message" id="message" class="form textarea"  placeholder="Uw bericht"></textarea>
                        </div>
                        <div class="relative fullwidth col-xs-12">
                            <button type="submit" id="submit" name="submit" class="form-btn semibold">Verzenden</button>
                        </div>
                        <div class="clear"></div>
                    </form>
                </div>
            </div>
        </div>
      <div style="float:right">
          <h2>Contactgegevens: </h2>
          TempoVideo <br>
          info@tempovideo.nl <br>
          088-1234567
      </div>
    </div>
  </div>
</div>


<?php
echo($_POST['naam']);
echo($_POST['email']);
echo($_POST['bericht']);
