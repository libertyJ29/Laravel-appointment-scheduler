@extends('layouts.master')
@section('content')

<html ng-app="dentistsApp">
    <form>
        <h3>
            Treatments Available at our practice
        </h3>
	<button class="btn btn-info margin-top-15" onclick="window.location='{!! route('dentists.index'); !!}'"><span class="glyphicon glyphicon-chevron-left"></span> Go Back</button>
	<br><br>
        @{{$scope.checker = false;	{{--default before ng-repeat--}} 
        scope.$apply();}}
        <b id="treatmentsCtrl" ng-controller="treatmentsCtrl" ng-cloak>
            <div class="form-group">
                <i>
                    <div class = "col-sm-1 col-sm-1-width200">Treatment</div>
                    <div class = "col-sm-2">Current Price</div>
                </i>
                <div class = "col-sm-9">
		    <i ng-model="scope.updatestatus" ng-show="showMessage">@{{updatestatus}}</i>
		</div>
            </div>
            <br>
            <div ng-repeat="treatment in data | orderBy : 'treatment'">	
		<br>
                    <label class="control-label col-sm-1 col-sm-1-width200">
                    @{{treatment.treatment}}</label>
                    <div class = "col-sm-2">
                        <input type="text" class="form-control" ng-model="treatment.price" name="currency">
                    </div>
                    <input class="btn btn-primary update-price-width" ng-click="updateTreatmentPrice(treatment.id, treatment.price, treatment.treatment)" value="Update Price">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#@{{treatment.id}}">DELETE Treatment</button>
                    <!-- Modal -->
                    <div class="modal fade" id="@{{treatment.id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel@{{treatment.id}}">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel@{{treatment.id}}">Are you sure you want to delete this Treatment record for @{{treatment.treatment}}?</h4>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <input class="btn btn-danger" ng-click="deleteThisTreatment(treatment.id);" value="Delete this Treatment" data-dismiss="modal">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </form>
    <!--Add new treatment to db-->
    <div class = "treatments-padding-left">
        <form name="newtreatment" ng-submit="addNewTreatment(treatment_name)">
            <div class = "col-sm-5">
                <input class="form-control formcontrol-input-addnewtreatment-width" type="text" ng-model="treatment_name" placeholder="Enter New Treatment Here">
                <input class="btn btn-primary" type="submit" value="Add New Treatment">
            </div>
        </form>
    </div>
    </b>
    <br><br>
    <!--get all treatments using angular-->
    <script>
        $(function(){
        	angular.element('#treatmentsCtrl').scope().getAllTreatments();
        });
    </script>

@stop