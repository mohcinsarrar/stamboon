"use strict";!function(){let o,t,e,r,a;isDarkStyle?(o=config.colors_dark.cardColor,r=config.colors_dark.textMuted,e=config.colors_dark.bodyColor,t=config.colors_dark.headingColor,a=config.colors_dark.borderColor):(o=config.colors.cardColor,r=config.colors.textMuted,e=config.colors.bodyColor,t=config.colors.headingColor,a=config.colors.borderColor);const i=document.querySelector("#weeklyEarningReports"),s={chart:{height:202,parentHeightOffset:0,type:"bar",toolbar:{show:!1}},plotOptions:{bar:{barHeight:"60%",columnWidth:"38%",startingShape:"rounded",endingShape:"rounded",borderRadius:4,distributed:!0}},grid:{show:!1,padding:{top:-30,bottom:0,left:-10,right:-10}},colors:[config.colors_label.primary,config.colors_label.primary,config.colors_label.primary,config.colors_label.primary,config.colors.primary,config.colors_label.primary,config.colors_label.primary],dataLabels:{enabled:!1},series:[{data:[40,65,50,45,90,55,70]}],legend:{show:!1},xaxis:{categories:["Mo","Tu","We","Th","Fr","Sa","Su"],axisBorder:{show:!1},axisTicks:{show:!1},labels:{style:{colors:r,fontSize:"13px",fontFamily:"Public Sans"}}},yaxis:{labels:{show:!1}},tooltip:{enabled:!1},responsive:[{breakpoint:1025,options:{chart:{height:199}}}]};if(void 0!==typeof i&&null!==i){new ApexCharts(i,s).render()}const n=document.querySelector("#supportTracker"),l={series:[85],labels:["Completed Task"],chart:{height:360,type:"radialBar"},plotOptions:{radialBar:{offsetY:10,startAngle:-140,endAngle:130,hollow:{size:"65%"},track:{background:o,strokeWidth:"100%"},dataLabels:{name:{offsetY:-20,color:r,fontSize:"13px",fontWeight:"400",fontFamily:"Public Sans"},value:{offsetY:10,color:t,fontSize:"38px",fontWeight:"600",fontFamily:"Public Sans"}}}},colors:[config.colors.primary],fill:{type:"gradient",gradient:{shade:"dark",shadeIntensity:.5,gradientToColors:[config.colors.primary],inverseColors:!0,opacityFrom:1,opacityTo:.6,stops:[30,70,100]}},stroke:{dashArray:10},grid:{padding:{top:-20,bottom:5}},states:{hover:{filter:{type:"none"}},active:{filter:{type:"none"}}},responsive:[{breakpoint:1025,options:{chart:{height:330}}},{breakpoint:769,options:{chart:{height:280}}}]};if(void 0!==typeof n&&null!==n){new ApexCharts(n,l).render()}const p=document.querySelector("#salesLastMonth"),d={series:[{name:"Sales",data:[32,27,27,30,25,25]},{name:"Visits",data:[25,35,20,20,20,20]}],chart:{height:306,type:"radar",toolbar:{show:!1}},plotOptions:{radar:{polygons:{strokeColors:a,connectorColors:a}}},stroke:{show:!1,width:0},legend:{show:!0,fontSize:"13px",position:"bottom",labels:{colors:e,useSeriesColors:!1},markers:{height:10,width:10,offsetX:-3},itemMargin:{horizontal:10},onItemHover:{highlightDataSeries:!1}},colors:[config.colors.primary,config.colors.info],fill:{opacity:[1,.85]},markers:{size:0},grid:{show:!1,padding:{top:0,bottom:-5}},xaxis:{categories:["Jan","Feb","Mar","Apr","May","Jun"],labels:{show:!0,style:{colors:[r,r,r,r,r,r],fontSize:"13px",fontFamily:"Public Sans"}}},yaxis:{show:!1,min:0,max:40,tickAmount:4},responsive:[{breakpoint:769,options:{chart:{height:400}}}]};if(void 0!==typeof p&&null!==p){new ApexCharts(p,d).render()}const c=document.querySelector("#totalRevenueChart"),h={series:[{name:"Earning",data:[270,210,180,200,250,280,250,270,150]},{name:"Expense",data:[-140,-160,-180,-150,-100,-60,-80,-100,-180]}],chart:{height:350,parentHeightOffset:0,stacked:!0,type:"bar",toolbar:{show:!1}},tooltip:{enabled:!1},plotOptions:{bar:{horizontal:!1,columnWidth:"40%",borderRadius:9,startingShape:"rounded",endingShape:"rounded"}},colors:[config.colors.primary,config.colors.warning],dataLabels:{enabled:!1},stroke:{curve:"smooth",width:6,lineCap:"round",colors:[o]},legend:{show:!0,horizontalAlign:"left",position:"top",fontFamily:"Public Sans",markers:{height:12,width:12,radius:12,offsetX:-3,offsetY:2},labels:{colors:e},itemMargin:{horizontal:5}},grid:{show:!1,padding:{bottom:-8,top:20}},xaxis:{categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep"],labels:{style:{fontSize:"13px",colors:r,fontFamily:"Public Sans"}},axisTicks:{show:!1},axisBorder:{show:!1}},yaxis:{labels:{offsetX:-16,style:{fontSize:"13px",colors:r,fontFamily:"Public Sans"}},min:-200,max:300,tickAmount:5},responsive:[{breakpoint:1700,options:{plotOptions:{bar:{columnWidth:"43%"}}}},{breakpoint:1441,options:{plotOptions:{bar:{columnWidth:"50%"}}}},{breakpoint:1300,options:{plotOptions:{bar:{columnWidth:"62%"}}}},{breakpoint:991,options:{plotOptions:{bar:{columnWidth:"38%"}}}},{breakpoint:850,options:{plotOptions:{bar:{columnWidth:"50%"}}}},{breakpoint:449,options:{plotOptions:{bar:{columnWidth:"73%"}},xaxis:{labels:{offsetY:-5}}}},{breakpoint:394,options:{plotOptions:{bar:{columnWidth:"88%"}}}}],states:{hover:{filter:{type:"none"}},active:{filter:{type:"none"}}}};if(void 0!==typeof c&&null!==c){new ApexCharts(c,h).render()}const b=document.querySelector("#budgetChart"),f={chart:{height:100,toolbar:{show:!1},zoom:{enabled:!1},type:"line"},series:[{name:"Last Month",data:[20,10,30,16,24,5,40,23,28,5,30]},{name:"This Month",data:[50,40,60,46,54,35,70,53,58,35,60]}],stroke:{curve:"smooth",dashArray:[5,0],width:[1,2]},legend:{show:!1},colors:[a,config.colors.primary],grid:{show:!1,borderColor:a,padding:{top:-30,bottom:-15,left:25}},markers:{size:0},xaxis:{labels:{show:!1},axisTicks:{show:!1},axisBorder:{show:!1}},yaxis:{show:!1},tooltip:{enabled:!1}};if(void 0!==typeof b&&null!==b){new ApexCharts(b,f).render()}const g=document.querySelector("#projectStatusChart"),u={chart:{height:252,type:"area",toolbar:!1},markers:{strokeColor:"transparent"},series:[{data:[2e3,2e3,4e3,4e3,3050,3050,2e3,2e3,3050,3050,4700,4700,2750,2750,5700,5700]}],dataLabels:{enabled:!1},grid:{show:!1,padding:{left:-10,right:-5}},stroke:{width:3,curve:"straight"},colors:[config.colors.primary],fill:{type:"gradient",gradient:{opacityFrom:.6,opacityTo:.15,stops:[0,95,100]}},xaxis:{labels:{show:!1},axisBorder:{show:!1},axisTicks:{show:!1},lines:{show:!1}},yaxis:{labels:{show:!1},min:1e3,max:6e3,tickAmount:5},tooltip:{enabled:!1}};if(void 0!==typeof g&&null!==g){new ApexCharts(g,u).render()}function m(o,t){const i=config.colors_label.primary,s=config.colors.primary;var n=[];for(let e=0;e<o.length;e++)e===t?n.push(s):n.push(i);return{chart:{height:258,parentHeightOffset:0,type:"bar",toolbar:{show:!1}},plotOptions:{bar:{columnWidth:"32%",startingShape:"rounded",borderRadius:7,distributed:!0,dataLabels:{position:"top"}}},grid:{show:!1,padding:{top:0,bottom:0,left:-10,right:-10}},colors:n,dataLabels:{enabled:!0,formatter:function(o){return o+"k"},offsetY:-25,style:{fontSize:"15px",colors:[e],fontWeight:"600",fontFamily:"Public Sans"}},series:[{data:o}],legend:{show:!1},tooltip:{enabled:!1},xaxis:{categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep"],axisBorder:{show:!0,color:a},axisTicks:{show:!1},labels:{style:{colors:r,fontSize:"13px",fontFamily:"Public Sans"}}},yaxis:{labels:{offsetX:-15,formatter:function(o){return"$"+parseInt(o/1)+"k"},style:{fontSize:"13px",colors:r,fontFamily:"Public Sans"},min:0,max:6e4,tickAmount:6}},responsive:[{breakpoint:1441,options:{plotOptions:{bar:{columnWidth:"41%"}}}},{breakpoint:590,options:{plotOptions:{bar:{columnWidth:"61%",borderRadius:5}},yaxis:{labels:{show:!1}},grid:{padding:{right:0,left:-20}},dataLabels:{style:{fontSize:"12px",fontWeight:"400"}}}}]}}var y=$.ajax({url:assetsPath+"json/earning-reports-charts.json",dataType:"json",async:!1}).responseJSON;const x=document.querySelector("#earningReportsTabsOrders"),w=m(y.data[0].chart_data,y.data[0].active_option);if(void 0!==typeof x&&null!==x){new ApexCharts(x,w).render()}const k=document.querySelector("#earningReportsTabsSales"),S=m(y.data[1].chart_data,y.data[1].active_option);if(void 0!==typeof k&&null!==k){new ApexCharts(k,S).render()}const v=document.querySelector("#earningReportsTabsProfit"),C=m(y.data[2].chart_data,y.data[2].active_option);if(void 0!==typeof v&&null!==v){new ApexCharts(v,C).render()}const O=document.querySelector("#earningReportsTabsIncome"),W=m(y.data[3].chart_data,y.data[3].active_option);if(void 0!==typeof O&&null!==O){new ApexCharts(O,W).render()}const A=document.querySelector("#totalEarningChart"),z={series:[{name:"Earning",data:[15,10,20,8,12,18,12,5]},{name:"Expense",data:[-7,-10,-7,-12,-6,-9,-5,-8]}],chart:{height:225,parentHeightOffset:0,stacked:!0,type:"bar",toolbar:{show:!1}},tooltip:{enabled:!1},legend:{show:!1},plotOptions:{bar:{horizontal:!1,columnWidth:"18%",borderRadius:5,startingShape:"rounded",endingShape:"rounded"}},colors:[config.colors.danger,config.colors.primary],dataLabels:{enabled:!1},grid:{show:!1,padding:{top:-40,bottom:-20,left:-10,right:-2}},xaxis:{labels:{show:!1},axisTicks:{show:!1},axisBorder:{show:!1}},yaxis:{labels:{show:!1}},responsive:[{breakpoint:1468,options:{plotOptions:{bar:{columnWidth:"22%"}}}},{breakpoint:1197,options:{chart:{height:228},plotOptions:{bar:{borderRadius:8,columnWidth:"26%"}}}},{breakpoint:783,options:{chart:{height:232},plotOptions:{bar:{borderRadius:6,columnWidth:"28%"}}}},{breakpoint:589,options:{plotOptions:{bar:{columnWidth:"16%"}}}},{breakpoint:520,options:{plotOptions:{bar:{borderRadius:6,columnWidth:"18%"}}}},{breakpoint:426,options:{plotOptions:{bar:{borderRadius:5,columnWidth:"20%"}}}},{breakpoint:381,options:{plotOptions:{bar:{columnWidth:"24%"}}}}],states:{hover:{filter:{type:"none"}},active:{filter:{type:"none"}}}};if(void 0!==typeof A&&null!==A){new ApexCharts(A,z).render()}}();