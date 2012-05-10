<script type="text/javascript">
        $(function() {

        $( "#amount_slider" ).slider({
            orientation: "horizontal",
            range: false,
            min: 1000,
            max: 300000,
            value: 100000,
            step: 1000,
            slide: function( event, ui ) {
                    $( "#amount" ).text( ui.value );
            },
            stop: function( event, ui ) {
                    calculateMorgage();
            }
        });

        $( "#amount" ).text($( "#amount_slider" ).slider( "value" ));

        $( "#interest_slider" ).slider({
            orientation: "horizontal",
            range: false,
            min: 1,
            max: 10,
            value: 5,
            step: 0.25,
            slide: function( event, ui ) {
                    $( "#interest" ).text( ui.value );
            },
            stop: function( event, ui ) {
                    calculateMorgage();
            }
        });

        $( "#interest" ).text($( "#interest_slider" ).slider( "value" ));

        $( "#time_slider" ).slider({
            orientation: "horizontal",
            range: false,
            min: 1,
            max: 30,
            value: 15,
            slide: function( event, ui ) {
                    $( "#time" ).text( ui.value );
            },
            stop: function( event, ui ) {
                    calculateMorgage();
            }
        });

        $( "#time" ).text($( "#time_slider" ).slider( "value" ));

        function calculateMorgage() {

            var amount   = $( "#amount_slider" ).slider( "value" );
            var interest = $( "#interest_slider" ).slider( "value" ) / 1200;
            var time     = $( "#time_slider" ).slider( "value" ) * 12;

            var rate     = amount * (interest * Math.pow(1+interest,time)) / (Math.pow(1+interest,time)-1);

            $( "#result" ).text(rate.toFixed(2));
        }

        calculateMorgage();

    });
    </script>

   