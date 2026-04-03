<section id='contatti' class='editor-widget' data-widget='contatti'>
  <script>
  $( function() {
  $( ".fb-primary p" ).addClass('fb-primary');
    $( ".fb-primary h1" ).addClass('fb-primary');
  } );
  </script>
<?php
// stampa il titolo se richiesto

		echo "<div class='f-flex fd-column  fb-primary'>"; 
		if (TMP::$tcttit > ' ') { echo "<h1>".TMP::$tcttit."</h1>"; } 
		if (TMP::$tcttext > ' ') { echo "<p>".TMP::$tcttext."</p>"; }
		echo "</div>";

?>            
			<div class="f-flex fd-row  fb-secondary">             
			<div class="f-flex fd-column f-dim1">
			<form id="contatti" method="post">
                <div> 
                      <div id="nome-block" class="form-group">
                        <label class="control-label" for="nome" id="nome-label">Nome (*) </label>
                        <input type="text" class="form-control" id="nome" placeholder="Inserisci Nome">
                        </div>
                </div>
                        
                <div> 
                      <div id="cognome-block" class="form-group">
                        <label class="control-label" for="cognome" id="cognome-label">Cognome (*) </label>
                        <input type="text" class="form-control" id="cognome" placeholder="Inserisci Cognome">
                      </div>
                </div>
                        
              <div> 
                  <div id="email-block" class="form-group">
                    <label class="control-label" for="email" id="email-label">Email (*) </label>
                    <input type="text" class="form-control" id="email" placeholder="Inserisci Email">
                  </div>
             </div>
                     
             <div> 
                  <div id="telefono-block" class="form-group">
                    <label class="control-label" for="telefono" id="telefono-label">Telefono </label>
                    <input type="text" class="form-control" id="telefono" placeholder="Inserisci Telefono">
                  </div>
            </div>
                    
                   
            <div>
                <div id="messaggio-block" class="form-group">   
                    <label class="control-label" id="messaggio-label" for="messaggio"> Messaggio (*)<br />Max. 200 caratteri.</label><br>
                    
					<textarea style="width:60%; min-height:200px" id="messaggio" name="messaggio" maxlength="200" placeholder="Inserisci Messaggio"></textarea>
                </div>
            </div>    
		</div>
<!-- ==   == -->
<div class="f-flex fd-column f-dim1">
<img src="https://unsplash.it/640/425" alt="?" border="0" align="left" width="?" height="?" />
</div>
</div>		

<!-- ================================================================================= -->

 <div class="f-flex fd-row jc-between fb-secondary">                  
            <div>
            	<div id="risultato-block" class="form-group">  
				<label class="label30" id="risultato-label" for="risultato">Inserisci il risultato 
                  <br />Per dimostrare che sei un umano.</label>
                    <input name="addendo1" type="text" id="addendo1" size="1" readonly="" value="1"> +   
                    <input name="addendo2" type="text" id="addendo2" size="1" readonly="" value="1"> =
                    <input name="risultato" type="text" id="risultato" size="2"> 
			  
                </div>
           </div>  
     
            <div>    
                    <button id="submitButton" type="submit" class="widget ui-button ui-corner-all">Invia</button>
            </div>
                    
             <div>  
                  
                    <label for="privacy" id="privacy-label">Acconsento al trattamento dei miei dati personali in base alle normative attualmente vigenti. </label></div>
					<div id="privacy-block" class="checkbox form-group">
                      <input type="checkbox" name="privacy" id="privacy" class="selector ui-checkboxradio ui-corner-all">         
                 </div>
             
 		</div>     <!-- row --> 
<!-- ================================================================================= -->		       
		</form>
		<script type="text/javascript">
$( ".selector" ).checkboxradio({
  classes: {
    "ui-checkboxradio": "highlight"
  }
});
		</script> 
		<script type="text/javascript">
		$( function() {
    $( "input" ).checkboxradio();
  } );
  </script>
    <script>
  $( function() {
    $( ".widget input[type=submit], .widget a, .widget button" ).button();
    $( "button, input, a" ).click( function( event ) {
      event.preventDefault();
    } );
  } );
  </script>
</section>