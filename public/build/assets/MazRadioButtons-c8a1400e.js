import{z as u,o as i,e as d,F as m,h as b,n as s,A as v,a as g,r as p,g as f,t as h}from"./app-dc519e83.js";(function(){try{if(typeof document<"u"){var e=document.createElement("style");e.appendChild(document.createTextNode(".m-radio-buttons[data-v-04dc546b]{display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;gap:.5rem}.m-radio-buttons__items[data-v-04dc546b]{cursor:pointer;border-radius:var(--maz-border-radius);padding:.5rem 1rem;font-weight:500;-webkit-transition-property:color,background-color,border-color,text-decoration-color,fill,stroke;transition-property:color,background-color,border-color,text-decoration-color,fill,stroke;-webkit-transition-timing-function:cubic-bezier(.4,0,.2,1);transition-timing-function:cubic-bezier(.4,0,.2,1);-webkit-transition-duration:.3s;transition-duration:.3s;-webkit-box-shadow:0 5px 10px 0 hsla(0,0%,0%,.05);box-shadow:0 5px 10px #0000000d}.m-radio-buttons__items[data-v-04dc546b]:not(.--is-selected):hover{background-color:var(--maz-color-bg-light)}html.dark .m-radio-buttons__items[data-v-04dc546b]:not(.--is-selected){background-color:var(--maz-color-bg-light)}html.dark .m-radio-buttons__items[data-v-04dc546b]:not(.--is-selected):hover{background-color:var(--maz-color-bg-lighter)}")),document.head.appendChild(e)}}catch(o){console.error("vite-plugin-css-injected-by-js",o)}})();const y=["id"],x=["id","name","value","onChange"],k=u({__name:"MazRadioButtons",props:{modelValue:{type:[String,Number],default:void 0},options:{type:Array,required:!0},name:{type:String,default:"MazButtonsRadio"},color:{type:String,default:"primary"},noElevation:{type:Boolean,default:!1},orientation:{type:String,default:"row"}},emits:["update:model-value","change"],setup(e,{emit:o}){const n=e,l=r=>{o("update:model-value",r.value)},a=r=>n.modelValue===r;return(r,w)=>(i(),d("div",{class:s(["m-radio-buttons",[e.orientation==="row"?"maz-flex-row":"maz-flex-col"]])},[(i(!0),d(m,null,b(e.options,(t,c)=>(i(),d("label",{id:t.value.toString(),key:c,class:s(["m-radio-buttons__items",{"--is-selected":a(t.value),"maz-elevation":!e.noElevation}]),style:v(a(t.value)?{color:`var(--maz-color-${e.color}-contrast)`,backgroundColor:`var(--maz-color-${e.color})`}:void 0)},[g("input",{id:t.value.toString(),type:"radio",name:e.name,value:t.value,class:"maz-hidden",onChange:_=>l(t)},null,40,x),p(r.$slots,"default",{option:t,selected:a(t.value)},()=>[f(h(t.label),1)],!0)],14,y))),128))],2))}}),z=(e,o)=>{const n=e.__vccOpts||e;for(const[l,a]of o)n[l]=a;return n},B=z(k,[["__scopeId","data-v-04dc546b"]]);export{B as M};