// JavaScript Document
function findObj(n, d) { //v4.01
  var p,i,x;  
  if(!d) d=document; 
  	if((p=n.indexOf("?"))>0&&parent.frames.length) {
    	d=parent.frames[n.substring(p+1)].document; 
		n=n.substring(0,p);
	}
  if(!(x=d[n])&&d.all) x=d.all[n]; 
  for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function showHideLayers() { //v6.0
  var i,p,v,obj,args=showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  	if ((obj=findObj(args[i]))!=null) { 
		v=args[i+2];
    	if (obj.style) { 
			obj=obj.style; 
			v=(v=='show')?'visible':(v=='hide')?'hidden':v; 
		}
    	obj.visibility=v; 
	}
}
