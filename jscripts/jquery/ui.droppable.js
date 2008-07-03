(function(A){A.fn.extend({droppable:function(C){var B=Array.prototype.slice.call(arguments,1);return this.each(function(){if(typeof C=="string"){var D=A.data(this,"droppable");if(D){D[C].apply(D,B)}}else{if(!A.data(this,"droppable")){new A.ui.droppable(this,C)}}})}});A.ui.droppable=function(D,B){this.element=A(D);A.data(D,"droppable",this);this.element.addClass("ui-droppable");var E=this.options=B=A.extend({},A.ui.droppable.defaults,B);var C=E.accept;E=A.extend(E,{accept:E.accept&&E.accept.constructor==Function?E.accept:function(F){return A(F).is(C)}});A(D).bind("setData.droppable",function(G,F,H){E[F]=H}).bind("getData.droppable",function(G,F){return E[F]});this.proportions={width:this.element.outerWidth(),height:this.element.outerHeight()};A.ui.ddmanager.droppables.push({item:this,over:0,out:1})};A.extend(A.ui.droppable,{defaults:{disabled:false,tolerance:"intersect"}});A.extend(A.ui.droppable.prototype,{plugins:{},ui:function(B){return{instance:this,draggable:(B.currentItem||B.element),helper:B.helper,position:B.position,absolutePosition:B.positionAbs,options:this.options,element:this.element}},destroy:function(){var B=A.ui.ddmanager.droppables;for(var C=0;C<B.length;C++){if(B[C].item==this){B.splice(C,1)}}this.element.removeClass("ui-droppable ui-droppable-disabled").removeData("droppable").unbind(".droppable")},enable:function(){this.element.removeClass("ui-droppable-disabled");this.options.disabled=false},disable:function(){this.element.addClass("ui-droppable-disabled");this.options.disabled=true},over:function(C){var B=A.ui.ddmanager.current;if(!B||(B.currentItem||B.element)[0]==this.element[0]){return }if(this.options.accept.call(this.element,(B.currentItem||B.element))){A.ui.plugin.call(this,"over",[C,this.ui(B)]);this.element.triggerHandler("dropover",[C,this.ui(B)],this.options.over)}},out:function(C){var B=A.ui.ddmanager.current;if(!B||(B.currentItem||B.element)[0]==this.element[0]){return }if(this.options.accept.call(this.element,(B.currentItem||B.element))){A.ui.plugin.call(this,"out",[C,this.ui(B)]);this.element.triggerHandler("dropout",[C,this.ui(B)],this.options.out)}},drop:function(D,C){var B=C||A.ui.ddmanager.current;if(!B||(B.currentItem||B.element)[0]==this.element[0]){return }var E=false;this.element.find(".ui-droppable").each(function(){var F=A.data(this,"droppable");if(F.options.greedy&&A.ui.intersect(B,{item:F,offset:F.element.offset()},F.options.tolerance)){E=true;return false}});if(E){return }if(this.options.accept.call(this.element,(B.currentItem||B.element))){A.ui.plugin.call(this,"drop",[D,this.ui(B)]);this.element.triggerHandler("drop",[D,this.ui(B)],this.options.drop)}},activate:function(C){var B=A.ui.ddmanager.current;A.ui.plugin.call(this,"activate",[C,this.ui(B)]);if(B){this.element.triggerHandler("dropactivate",[C,this.ui(B)],this.options.activate)}},deactivate:function(C){var B=A.ui.ddmanager.current;A.ui.plugin.call(this,"deactivate",[C,this.ui(B)]);if(B){this.element.triggerHandler("dropdeactivate",[C,this.ui(B)],this.options.deactivate)}}});A.ui.intersect=function(L,F,J){if(!F.offset){return false}var D=L.positionAbs.left,C=D+L.helperProportions.width,I=L.positionAbs.top,H=I+L.helperProportions.height;var E=F.offset.left,B=E+F.item.proportions.width,K=F.offset.top,G=K+F.item.proportions.height;switch(J){case"fit":if(!((H-(L.helperProportions.height/2)>K&&I<K)||(I<G&&H>G)||(C>E&&D<E)||(D<B&&C>B))){return false}if(H-(L.helperProportions.height/2)>K&&I<K){return 1}if(I<G&&H>G){return 2}if(C>E&&D<E){return 1}if(D<B&&C>B){return 2}break;case"intersect":return(E<D+(L.helperProportions.width/2)&&C-(L.helperProportions.width/2)<B&&K<I+(L.helperProportions.height/2)&&H-(L.helperProportions.height/2)<G);break;case"pointer":return(E<(L.positionAbs.left+L.clickOffset.left)&&(L.positionAbs.left+L.clickOffset.left)<B&&K<(L.positionAbs.top+L.clickOffset.top)&&(L.positionAbs.top+L.clickOffset.top)<G);break;case"touch":return((I>=K&&I<=G)||(H>=K&&H<=G)||(I<K&&H>G))&&((D>=E&&D<=B)||(C>=E&&C<=B)||(D<E&&C>B));break;default:return false;break}};A.ui.ddmanager={current:null,droppables:[],prepareOffsets:function(D,F){var B=A.ui.ddmanager.droppables;var E=F?F.type:null;for(var C=0;C<B.length;C++){if(B[C].item.options.disabled||(D&&!B[C].item.options.accept.call(B[C].item.element,(D.currentItem||D.element)))){continue}B[C].offset=A(B[C].item.element).offset();B[C].item.proportions={width:B[C].item.element.outerWidth(),height:B[C].item.element.outerHeight()};if(E=="dragstart"){B[C].item.activate.call(B[C].item,F)}}},drop:function(B,C){A.each(A.ui.ddmanager.droppables,function(){if(!this.item.options.disabled&&A.ui.intersect(B,this,this.item.options.tolerance)){this.item.drop.call(this.item,C)}if(!this.item.options.disabled&&this.item.options.accept.call(this.item.element,(B.currentItem||B.element))){this.out=1;this.over=0;this.item.deactivate.call(this.item,C)}})},drag:function(B,C){if(B.options.refreshPositions){A.ui.ddmanager.prepareOffsets(B,C)}A.each(A.ui.ddmanager.droppables,function(){if(this.item.disabled||this.greedyChild){return }var E=A.ui.intersect(B,this,this.item.options.tolerance);var F=!E&&this.over==1?"out":(E&&this.over==0?"over":null);if(!F){return }var D=A.data(this.item.element[0],"droppable");if(D.options.greedy){this.item.element.parents(".ui-droppable").each(function(){var G=this;A.each(A.ui.ddmanager.droppables,function(){if(this.item.element[0]!=G){return }this[F]=0;this[F=="out"?"over":"out"]=1;this.greedyChild=(F=="over"?1:0);this.item[F=="out"?"over":"out"].call(this.item,C);return false})})}this[F]=1;this[F=="out"?"over":"out"]=0;this.item[F].call(this.item,C)})}};A.ui.plugin.add("droppable","activeClass",{activate:function(C,B){A(this).addClass(B.options.activeClass)},deactivate:function(C,B){A(this).removeClass(B.options.activeClass)},drop:function(C,B){A(this).removeClass(B.options.activeClass)}});A.ui.plugin.add("droppable","hoverClass",{over:function(C,B){A(this).addClass(B.options.hoverClass)},out:function(C,B){A(this).removeClass(B.options.hoverClass)},drop:function(C,B){A(this).removeClass(B.options.hoverClass)}})})(jQuery);