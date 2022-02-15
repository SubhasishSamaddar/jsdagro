<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enter Your Pincode Here</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!--form-->
                    <div class="form-group">
                        <input type="text" id="swadesh_hut_id" class="form-control" placeholder="Enter Your Pincode" onblur="getSwadeshHut()">
                    </div>
					<span id="msgstorename"></span>
                    <!--button type="submit" class="btn btn-primary">Start Shopping</button>
                </form-->
            </div>
        </div>
    </div>
</div>
</body>
</html>
Front Footer
@if(!Session::has('swadesh_hut_name')) 
	<script>
	$(document).ready(function(){
        $("#myModal").modal('show');
    });
	</script>
@endif
<script>
	 function setInputFilter(textbox, inputFilter) {
    ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
        textbox.addEventListener(event, function() {
        if (inputFilter(this.value)) {
            this.oldValue = this.value;
            this.oldSelectionStart = this.selectionStart;
            this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
            this.value = this.oldValue;
            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
            this.value = "";
        }
        });
    });
    }
	setInputFilter(document.getElementById("swadesh_hut_id"), function(value) {
		return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    });
	function getSwadeshHut(){
		var ID=$("#swadesh_hut_id").val();
		//alert(ID);
		$("#msgstorename").html('');
		$.ajax({      
			type: "GET",
			url: "{{ url('get-swadesh-hut') }}",   
			data: {pincode : ID},       
			success:   
			function(data){  
				var jsonData = $.parseJSON(data);
				if(jsonData.status=='1'){
					$("#cartstoreid").html(jsonData.msg);
					$("#msgstorename").html(jsonData.msg);
					$("#processtocheckout").prop('disabled', false);
				}else{
					$("#msgstorename").html(jsonData.msg);
				}
				//location.reload();
			}
		});
		
	}
</script>
