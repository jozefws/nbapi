  
            //lambda forall in array not empty js
            function allNotEmpty(array) {
                for (var i = 0; i < array.length; i++) {
                    if (getCookie(array[i]) == "") {
                        return false;
                    }
                }
                return true;
            }
            if(allNotEmpty(list)){
                document.getElementById("wire_submit").disabled = false;
                document.getElementById("wire_submit").style.opacity = 1;
            }else{
                document.getElementById("wire_submit").disabled = true;
                document.getElementById("wire_submit").style.opacity = 0.5;
            }

            function clearInfOptions(sides){
                var list = document.getElementById("inteface_dl_" + sides.toLowerCase());
                for (var i = 0; i < list.options.length; i++) {
                    list.options[i] = null;
                }
            }
    


            function roomSelected(){
                var room = document.getElementById("roomSel");
                var roomID = room.selectedIndex;
                document.cookie = "rackA=";
                document.cookie = "rackB=";
                room = room.options[roomID].value;
                if(room == ""){
                    document.cookie = "room=";
                    document.getElementById("rackSelA").disabled = true;
                    document.getElementById("rackSelB").disabled = true;
                    document.getElementById("deviceSelA").disabled = true;
                    document.getElementById("deviceSelB").disabled = true;
                    document.getElementById("interfaceSelA").disabled = true;
                    document.getElementById("interfaceSelB").disabled = true;

                    return;
                }else{
                    document.cookie = "room=" + room;
                    document.getElementById("rackSelA").disabled = false;
                    document.getElementById("rackSelB").disabled = false;
                    window.location.reload();
                }
            }
            
            function rackSelected(side){
                var rack = document.getElementById("rackSel" + side.toUpperCase());
                var rackID = rack.selectedIndex;
                rack = rack.options[rackID].value;
                document.cookie = "device"+side.toUpperCase()+"=";
                if(rack == ""){
                    document.cookie = "rack"+side.toUpperCase()+"=";
                    document.getElementById("deviceSel" + side.toUpperCase()).disabled = true;
                    return;
                }else{
                    if(side.toUpperCase() == "A"){
                        document.getElementById("deviceSelA").disabled = false;
                        document.cookie = "rackA=" + rack;
                        
                    
                    }else if(side.toUpperCase() == "B"){                    
                        document.getElementById("deviceSelB").disabled = false;
                        document.cookie = "rackB=" + rack;
                    }
                    
                }
                window.location.reload();

            }

            function deviceSelected(side){
                var device = document.getElementById("deviceSel" + side.toUpperCase());
                var deviceID = device.selectedIndex;
                device = device.options[deviceID].value;
                document.cookie = "interface"+side.toUpperCase()+"=";
                document.getElementById("interfaceSel" + side.toUpperCase()).value = "";
                if(device == ""){
                    return;
                }else{
                    if(side.toUpperCase() == "A"){
                        clearInfOptions("a");
                        document.getElementById("interfaceSelA").disabled = false;
                        document.cookie = "deviceA=" + device;
                    
                    }else if(side.toUpperCase() == "B"){  
                        clearInfOptions("b");                  
                        document.getElementById("interfaceSelB").disabled = false;
                        document.cookie = "deviceB=" + device;
                    }
                } 
                window.location.reload();
        }

            function interfaceSelected(side){
                var intfaceRef = document.getElementById("interfaceSel" + side.toUpperCase());
                var intface = intfaceRef.value;
                intfaceRef = intfaceRef.innerHTML;
                if(intface == ""){
                    document.cookie = "interface"+side.toUpperCase()+"=";
                    return;
                }else{
                    if(side.toUpperCase() == "A"){
                        document.cookie = "interfaceA=" + intface;
                    }else if(side.toUpperCase() == "B"){  
                        document.cookie = "interfaceB=" + intface;
                    }
                
                } 
                
            }

            function sides(side){
                if(side == "a"){
                    document.cookie = "side=a";
                    document.getElementById("sideB").style.display = "none";
                    document.getElementById("sideA").style.display = "inline-block";
                    document.getElementById("sideselA").style.borderBottom = "2px solid #fff";
                    document.getElementById("sideselB").style.borderBottom = "0px";
                }
                if(side == "b"){
                    document.cookie = "side=b";
                    document.getElementById("sideA").style.display = "none";
                    document.getElementById("sideB").style.display = "inline-block";
                    document.getElementById("sideselB").style.borderBottom = "2px solid #fff";
                    document.getElementById("sideselA").style.borderBottom = "0px";
                }
            }

            function redirectInterface(){
                var side = getCookie("side");
                if(side == ""){side = "a";}
                var url = "interfaceadd.php?room="+getCookie("room");
                if(getCookie("rack"+side.toUpperCase()) != ""){
                    url += "&rack="+getCookie("rack"+side.toUpperCase());
                }
                if(getCookie("device"+side.toUpperCase()) != ""){
                    url += "&device="+getCookie("device"+side.toUpperCase());
                }
                window.location = url;
            }
        
