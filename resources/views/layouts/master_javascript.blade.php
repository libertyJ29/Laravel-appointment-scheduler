<script>
$(function() {

    <!-- for patient records -->
    if( patientrecord == 'true')
    {
        $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy',  yearRange: "1920:current",  changeYear: true });
        if ( date_of != ""){
    	    $( "#datepicker" ).datepicker('setDate', date_of);
        }
    }

    <!-- for appointment records -->
    else if ( appointmentrecord == 'true')
    {
        $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy', 
        onClose: function(date) {			<!--display the number of appointments for each new datepicker selection-->
           angular.element('#appointmentsCtrl').scope().getappointmentsfordate(date);
    	} 
    	});
    	if( date_of != ""){
            $( "#datepicker" ).datepicker('setDate', date_of);
    	}
        angular.element('#appointmentsCtrl').scope().getappointmentsfordate(date_of); 	<!--display the number of appointments for date on initial page load-->
    }

    <!-- for appointments scheduler -->
    else if ( index == 'true')
    {
        var loadfrom1 = "0" ;
    
        <?php 
        //check that $loadfrom1 exists
        if ( isset($loadfrom1) && ($loadfrom1== '1') )
        {
            ?>
    	    var loadfrom1 = "1" ;
    	    <?php
        }
        ?>
    
        $( "#datepicker" ).datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
    
            onSelect: function(){
                var pickedday = $("#datepicker").datepicker('getDate');
       	        var dayuk = $.datepicker.formatDate("dd-mm-yy", pickedday);
       	        document.getElementById("date-fill").value = dayuk;
    
    	        <!--check if date picked equals current index date from page load - if not then reload the appointments index with new picked date-->
    	        if (date_of != dayuk){
        	    document.getElementById("output").innerHTML = "<i>loading calendar...</i>";
    
        	    <!--pass the 1 or 0 value from create/edit appointment as a parameter below-->
        	    window.location.href="../appointments/index1,1,"+type+","+dayuk+","+loadfrom1;
    		}
    
            }
        });
    }
    
    $( "#datepicker" ).datepicker('setDate', date_of);
    $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy'});
});
    
    
    
    
    
    
    
    
    
    
    
    
    
    
<!--Angular for Appointments section-->
angular.module('appointmentsApp', [])
    .controller('appointmentsCtrl', function($scope, $http) {
    
        $scope.gettreatmentprice = function(treatment_type) {
            var getTreatmentPriceUrl = "../appointments/gettreatmentprice";
    
            $http.get(getTreatmentPriceUrl+treatment_type)
    	    .success(function(treatmentprice){
    		$scope.treatment.newprice = treatmentprice;
    		$scope.$watch( 'checker');	<!--watch for any change of $scope.checker variable in the view and apply the change-->
    	    })
        }
    
    
        $scope.getappointmentsfordate = function(date) {
    	    var getAppointmentsUrl = "../appointments/getappointmentsfordate";
    
            $http.get(getAppointmentsUrl+date)
            .success(function(data){
                $scope.data = data;
                $scope.$watch( 'checker');	<!--watch for any change of $scope.checker variable in the view and apply the change-->
            })
        }
    
    
        <!-- mark as paid button on edit to set the angular payment_status and change the button to say "invoice paid"-->
        $scope.paidbutton = function(appointmentid) {
            var updatePaymentStatusUrl = "../appointments/paymentstatus" + appointmentid;
    
            $http.get(updatePaymentStatusUrl)
            .success(function(paymentstatusflag){
    	        $scope.paymentstatusflag = paymentstatusflag;	<!--load the payment status flag with 1 now its been set by clicking the button-->
    	        $scope.paidbuttontext = 'Invoice Paid in Full';
            })
        }
    
    });
    
    
    
    
    
    
    
    
    
    
<!--Angular for Dentists section-->
<!--add ngAnimate to fade out text-->
angular.module('dentistsApp', ['ngAnimate'])
    .controller('treatmentsCtrl', function ($scope, $http, $timeout) {
    
    	//init timer for fading out update status text, so that the timer is accessible by every update click
    	mytimer = $timeout();

        $scope.getAllTreatments = function() {
    	    var getTreatmentsUrl = "../dentists/getalltreatments";
    
            $http.get(getTreatmentsUrl)
            .success(function(data){
    		$scope.updatestatus = '';
            	$scope.data = data;
            	$scope.$watch( 'checker');	<!--watch for any change of $scope.checker variable in the view and apply the change-->
                })
            }
    
    
    	$scope.updateTreatmentPrice = function(id, newprice, treatment_name) {
    	    var updatePriceUrl = "../dentists/updatetreatments" + id + "," + newprice;
    
    	    $timeout.cancel(mytimer);
            $http.get(updatePriceUrl)
            .success(function(data){
    	        $scope.updatestatus = "You have Updated the treatment price for " + treatment_name;
    	        $scope.data = data;		<!--need to reload the data array for the view, so it is updated imediately after deleting the record-->
      	        $scope.showMessage = 'true';	<!--when timer fades out, this is set to false so the message will not show anymore-->
    	        mytimer=$timeout(function() {
    		    $scope.showMessage = false;
    	        }, 4000);
      	    })
    	    .error(function(){
    	        $scope.updatestatus = "The treatment price you entered for " + treatment_name + " is not valid! This price will not be updated.";
    	        $scope.showMessage = 'true';
    	        mytimer=$timeout();
    	    })
     	}
    
    
     	$scope.deleteThisTreatment = function(id) {
    	    var deleteTreatmentUrl = "../dentists/deletethistreatment" + id;
    
            $http.get(deleteTreatmentUrl)
            .success(function(data){
    	        $scope.data = data;		<!--need to reload the data array for the view, so it is updated imediately after deleting the record-->
     	    });
     	}
    
    
     	$scope.getAllTreatmentsWithVars = function() {
    	    var getAllTreatmentsUrl = "../dentists/getalltreatments";
    
            $http.get(getAllTreatmentsUrl)
            .success(function(data){
            	$scope.data = data;
    		$scope.selected = [];		<!--store the dentists selected treatments in the view-->
    		$scope.selected2 = '';		<!--store the individual checkbox values-->
    		$scope.treatment_name = '';	<!--store new treatment name-->
            	$scope.$watch( 'checker');	<!--watch for any change of $scope.checker variable in the view and apply the change-->
            })
     	}
    
    
     	$scope.addNewTreatment = function(treatment_name) {
    	    var addNewTreatmentUrl = "../dentists/addnewtreatment";
    
            $http.get(addNewTreatmentUrl+treatment_name)
            .success(function(data){
    	        <!--update the model displayed on create dentist page-->
    	        $scope.$$prevSibling.data = data;
    		$scope.selected = [];		<!--store the dentists selected treatments in the view-->
    		$scope.selected2 = '';		<!--store the individual checkbox values-->
    		$scope.treatment_name = '';	<!--store new treatment name-->
    		$scope.$watch( 'checker');	<!--watch for any change of $scope.checker variable in the view and apply the change-->
    	     })
     	}
    
    
    });
</script>














<script>
    <!-- show all patients - paging table -->
    $('table#patientsall').DataTable( {
      	"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
    
    
    <!-- show all appointments paging table -->
        $('table#appointmentsall').DataTable();
    
    
    <!--Popover on create/edit appointment-->
    $('[data-toggle="popover"]').popover({
        html: true
    }); 
</script>