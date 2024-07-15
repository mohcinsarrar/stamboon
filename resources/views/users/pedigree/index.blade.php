@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
@endsection

@section('page-style')
    <style>
        .person-member {
            display: flex;
            flex-wrap: wrap;
            height: 40px;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.24);
        }

        .male-member {
            background: radial-gradient(circle, #B0E0E6 0%, #00CED1 100%);
        }

        .female-member {
            background: radial-gradient(circle, #FFB6C1 0%, #FF69B4 100%);
        }

        div.line {
            margin-top: 18px;
            width: 2px;
            border-style: solid;
            border-width: 2px 0 0;
            border-color: lightgray;

        }

        div.line hr {
            border-style: solid;
            border-width: 5px 0 0;
        }

        .person-icon {
            padding-top: 3px;
            width: 40px;
            height: 30px;
        }

        .person-name {
            text-align: left;
            justify-content: center;
        }

        .selected-person {
            border: 4px solid #152785;
        }
    </style>
@endsection


@section('title', 'Pedigree')

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="https://scaleflex.cloudimg.io/v7/plugins/filerobot-image-editor/latest/filerobot-image-editor.min.js">
    </script>

    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/read-gedcom/dist/read-gedcom.min.js"></script>
    <!-- apex dTree -->
    <!-- required for dTree -->
    <!-- load dTree -->
    <!-- load treant -->
    <!-- d3-org-chart -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3-org-chart@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3-flextree@2.1.2/build/d3-flextree.js"></script>
    <!-- elgrapho -->

    
@endsection

@section('page-script')

    <script>
        const nodeWidth = 150;
        const nodeWidthSpouse = 320;
        const gapWidth = nodeWidthSpouse - nodeWidth * 2;
        const linkShift = Math.round((nodeWidth + gapWidth) / 2);
        const nodeHeight = 40;
        const nodeHeightSpouse = 40;
        const maleIcon = '/storage/portraits/male.png';
        const femaleIcon = '/storage/portraits/female.png';
        var chart;
        function draw_graph(data) {

            

            chart = new d3.OrgChart()
                .container("#graph")
                .data(data)
                .layout('top')
                .initialExpandLevel(5)
                .compact(false)
                .rootMargin(100)
                .nodeWidth((d) =>
                    d.data.spouseId !== undefined ? nodeWidthSpouse : nodeWidth
                )
                .nodeHeight((d) =>
                    d.data.spouseId !== undefined ? nodeHeightSpouse : nodeHeight
                )
                .childrenMargin((d) => 75)
                .siblingsMargin((d) => 60)
                .linkUpdate(function(d, i, arr) {
                    // drawing the connecting line
                    d3.select(this)
                        .attr('stroke', (d) => 'lightgray')
                        .attr('stroke-width', (d) => 2);
                    //.attr('stroke-dasharray', '4,4');
                    if(d.data.parentId == 'hidden_root'){
                        d3.select(this).style('visibility', 'hidden');
                    }

                })
                .nodeUpdate(function (d, i, arr) {
                    if(d.data.id == 'hidden_root'){
                        d3.select(this).style('visibility', 'hidden');
                    }
                    
                })
                .nodeContent(function(d, i, arr, state) {
                    const personData = d.data;

                    let nodeHtml = '<div class="row">';
                    if (personData.gender === 'F') {
                        nodeHtml += getPersonNodeContent(personData, 'spouse');
                        nodeHtml += getPersonNodeContent(personData, 'child');
                    } else {
                        nodeHtml += getPersonNodeContent(personData, 'child');
                        nodeHtml += getPersonNodeContent(personData, 'spouse');
                    }
                    nodeHtml += '</div>';

                    return nodeHtml;
                });
            

            chart.layoutBindings().top.linkX = (d) => {
                if (d.data.spouseId === undefined) {
                    return d.x;
                } else if (d.data.gender === 'M') {
                    return d.x - linkShift;
                } else {
                    return d.x + linkShift;
                }
            };




            chart.render().fit();

            $(document).on("click", "#zoomIn", function() {
                chart.zoomIn()
            });

            $(document).on("click", "#zoomOut", function() {
                chart.zoomOut()
            });

            $(document).on("click", "#viewVertical", function() {
                chart.layout('top').render().fit()
            });

            $(document).on("click", "#viewHorizontal", function() {
                chart.layout('left').render().fit()
            });

            
        }




        function getPersonNodeContent(personData, personType) {
            const person = {};

            if (personType === 'spouse') {
                person.id = personData.spouseId;
                person.name = personData.spouseName;
                person.gender = personData.spouseGender;
            } else {
                person.id = personData.id;
                person.name = personData.name;
                person.gender = personData.gender;
            }

            if (person.id === undefined) {
                return '';
            } else {
                let personCssClass, personIcon;
                if (person.gender === 'M') {
                    personCssClass = 'male-member';
                    personIcon = maleIcon;
                } else {
                    personCssClass = 'female-member';
                    personIcon = femaleIcon;
                }
                let nodeContent = '';
                if (personData.spouseId !== undefined && person.gender === 'F') {
                    nodeContent += '<div class="line"><hr/></div>';
                }
                nodeContent += `
                        <div class="col ${personCssClass} person-member person-${person.id}" onclick="window.selectedPersonId = '${person.id}';">
                            <div class="col-grow">
                            <img src="${personIcon}" class="person-icon" />
                        </div>
                        <div class="col person-name">${person.name}</div>
                        </div>`;
                return nodeContent;
                        /*
                 nodeHtml = `
                        <div style="padding-top:30px;background-color:none;margin-left:1px;height:${d.height}px;border-radius:2px;overflow:visible">
                        <div style="height:${
                            d.height - 32
                        }px;padding-top:0px;background-color:white;border:1px solid lightgray;">

                            <img src=" ${
                            d.data.imageUrl
                            }" style="margin-top:-30px;margin-left:${d.width / 2 - 30}px;border-radius:100px;width:60px;height:60px;" />

                        <div style="margin-right:10px;margin-top:15px;float:right">${
                            d.data.id
                        }</div>
                        
                        <div style="margin-top:-30px;background-color:#3AB6E3;height:10px;width:${
                            d.width - 2
                        }px;border-radius:1px"></div>

                        <div style="padding:20px; padding-top:35px;text-align:center">
                            <div style="color:#111672;font-size:16px;font-weight:bold"> ${
                                d.data.name
                            } </div>
                            <div style="color:#404040;font-size:16px;margin-top:4px"> ${
                                d.data.positionName
                            } </div>
                        </div> 
                        </div>     
                        </div>
                        `;*/
            }
        }


        function draw_tree() {


            const promise = fetch('/pedigree/getTree')
                .then(r => r.arrayBuffer())
                .then(Gedcom.readGedcom);

            promise.then(gedcom => {
                

                const treeData = transformGedcom(gedcom);
                /*
                const treeData = [{
                    id: 'L1',
                    name: 'Grand Father Kumar',
                    gender: 'M',
                    parentId: '',
                    spouseId: 'L1S',
                    spouseName: 'Grand Mother Kumar',
                    spouseGender: 'F',
                },
                {
                    id: 'L1C1',
                    name: 'Son 1 Kumar',
                    gender: 'M',
                    parentId: 'L1',
                    spouseId: 'L1C1S',
                    spouseName: 'Daughter in law',
                    spouseGender: 'F',
                },
                {
                    id: 'L1C2',
                    name: 'Daughter Devi',
                    gender: 'F',
                    parentId: 'L1',
                    spouseId: 'L1C2S',
                    spouseName: 'Son in law',
                    spouseGender: 'M',
                },
                ]
                */
                draw_graph(treeData)

            });

        }

        function isObject(variable) {
            return typeof variable === 'object' && variable !== null;
        }
        
        function get_death_date(individual){
            if(individual.getEventDeath().length <= 0){
                return null
            }
            if(individual.getEventDeath().getDate().length <= 0){
                return null
            }

            return individual.getEventDeath().getDate()[0].value
        }

        function get_birth_date(individual){
            if(individual.getEventBirth().length <= 0){
                return null
            }
            if(individual.getEventBirth().getDate().length <= 0){
                return null
            }

            return individual.getEventBirth().getDate()[0].value
        }
        
        function transformGedcom(gedcom){

            const individualRecords = [];

            // iterate families
            families = gedcom.getFamilyRecord().arraySelect()
            families.forEach((family, key, array) => {

                // check if family is an object
                if (!isObject(family[0])) {
                    return;
                }

                

                // get husband and wife
                husband = family.getHusband().getIndividualRecord()
                wife = family.getWife().getIndividualRecord()
                if(family[0].pointer == '@F85@'){
                    console.log(wife.getFamilyAsChild().length)
                }
                
                // check wich is a parent husband or wife
                let parent = {}
                let spouse = {}
                /// check if husband is child in another family so it's added as parent to children in this family
                if(husband.getFamilyAsChild().length > 0){
                    parent = husband
                    spouse = wife
                }
                else if(wife.getFamilyAsChild().length > 0){
                    parent = wife
                    spouse = husband
                }
                else{
                    parent = husband
                    spouse = wife
                }

                

                // create a parent person from selected parent (husband or wife)
                let parentPerson = {}
                if(parent.length > 0){
                    if(spouse.length > 0){
                        parentPerson = {
                            id : parent[0].pointer,
                            name : ((parent.getName().length > 0) ? parent.getName()[0].value.replace(/\//g, " ").trimEnd() : ''),
                            gender : ((parent.getSex().length > 0) ? parent.getSex()[0].value : ''), 
                            birth : get_birth_date(parent),
                            death : get_death_date(parent) ,
                            spouseId: spouse[0].pointer,
                            spouseName: spouse.getName()[0].value.replace(/\//g, " ").trimEnd(),
                            spouseGender: spouse.getSex()[0].value,
                        };

                    }
                    else{
                        parentPerson = {
                            id : parent[0].pointer,
                            name : ((parent.getName().length > 0) ? parent.getName()[0].value.replace(/\//g, " ").trimEnd() : ''),
                            gender : ((parent.getSex().length > 0) ? parent.getSex()[0].value : ''), 
                            birth : get_birth_date(parent),
                            death : get_death_date(parent) ,
                        };

                    }
                }
                else{
                    if(spouse.length > 0){
                        parentPerson = {
                            id : spouse[0].pointer,
                            name : ((spouse.getName().length > 0) ? spouse.getName()[0].value.replace(/\//g, " ").trimEnd() : ''),
                            gender : ((spouse.getSex().length > 0) ? spouse.getSex()[0].value : ''), 
                            birth : get_birth_date(spouse),
                            death : get_death_date(spouse),
                        };
                    }
                    
                }

                if(Object.keys(parentPerson).length === 0){
                    return;
                }

                // parent dont already exist
                if(!(parentPerson.id in individualRecords)){
                    individualRecords[parentPerson.id] = parentPerson;
                }
                else{
                    individualRecords[parentPerson.id].name = parentPerson.name
                    individualRecords[parentPerson.id].gender = parentPerson.gender
                    individualRecords[parentPerson.id].birth = parentPerson.birth
                    individualRecords[parentPerson.id].death = parentPerson.death
                    if(parentPerson.hasOwnProperty('spouseId')){
                        individualRecords[parentPerson.id].spouseId = parentPerson.spouseId
                    }
                    if(parentPerson.hasOwnProperty('spouseName')){
                        individualRecords[parentPerson.id].spouseName = parentPerson.spouseName
                    }
                    if(parentPerson.hasOwnProperty('spouseGender')){
                        individualRecords[parentPerson.id].spouseGender = parentPerson.spouseGender
                    }
                }
                

                // iterate all children in the family
                children = family.getChild().getIndividualRecord().arraySelect()

                children.forEach((child, key, array) => {
                    pointer = child[0].pointer
                    // child dont already exist
                    if(!(pointer in individualRecords)){
                        const childPerson = {
                            id : pointer,
                            name : ((child.getName().length > 0) ? child.getName()[0].value.replace(/\//g, " ").trimEnd() : ''),
                            gender : ((child.getSex().length > 0) ? child.getSex()[0].value : ''), 
                            birth : get_birth_date(parent),
                            death : get_death_date(parent),
                            parentId : parentPerson.id
                        }
                        individualRecords[childPerson.id] = childPerson;
                    }
                    else{
                        individualRecords[pointer].parentId = parentPerson.id
                    }

                });

                

            });
            // add hidden route
            const hiddenRootNode = { id: "hidden_root", name: "Hidden Root", parentId: null };
            individualRecords[hiddenRootNode.id] = hiddenRootNode;

            const data = Object.values(individualRecords)
            data.forEach(member => {
                if (!member.hasOwnProperty('parentId')) {
                    member.parentId = hiddenRootNode.id;
                }
            });
            console.log(data)
            return(data)
        }



    </script>
    <script>
        draw_tree()
    </script>
    <script>
        $(document).on("click", "#import-gedcom", function() {
            const file = $('#gedcom').prop('files')[0];
            var modalElement = document.getElementById('uploadFile');
            var modal = bootstrap.Modal.getInstance(modalElement);
            

            if (!file) {
                show_toast('error','error','No file selected')
                return;
            }

            const formData = new FormData();
            formData.append('file', file);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/pedigree/importgedcom",
                type: 'POST',
                data: formData,
                encode: true,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    modal.hide();
                    if (data.error == false) {
                        show_toast('success','upload file',data.msg)
                        draw_tree()
                    } else {
                        show_toast('error','error',data.error)
                    }

                },
                error: function(xhr, status, error) {
                    modal.hide();
                    if ('responseJSON' in xhr) {
                        show_toast('error','error',xhr.responseJSON.message)
                    } else {
                        show_toast('error','error',error)
                    }

                    return null;
                }
            });

        });
    </script>


@endsection


@section('content')

    <div class="modal fade" id="uploadFile" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel1">Uplod your family tree</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="template" class="form-label">Import your Gedcom file</label>
                        <div class="input-group">
                            <input type="file" name="gedcom" id="gedcom" class="form-control" id="inputGroupFile04"
                                aria-describedby="import-gedcom" aria-label="Upload" autocomplete="off">
                            <button class="btn btn-outline-primary waves-effect" type="button"
                                id="import-gedcom">Import</button>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    <div class="card blocking-card">
        <div class="card-body position-relative">
            <div class="border position-absolute p-3 bg-secondary rounded">
                <div class="row mx-0 mb-2">
                    <button data-bs-toggle="modal" data-bs-target="#uploadFile" type="button" class="btn btn-outline-dark waves-effect text-white border-0 px-1"><i class="ti ti-upload fs-3"></i></button>
                </div>
                <div class="row mx-0 mb-2">
                    <div class="btn-group dropend">
                        <button type="button" class="btn btn-outline-dark dropdown-toggle waves-effect hide-arrow  text-white border-0 px-1" data-bs-toggle="dropdown" data-trigger="hover" aria-expanded="false"><i class="ti ti-growth  fs-3"></i></button>
                        <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header text-uppercase">View</h6></li>
                            <li><a class="dropdown-item waves-effect" href="javascript:void(0);" id="viewVertical">Vertical</a></li>
                            <li><a class="dropdown-item waves-effect" href="javascript:void(0);" id="viewHorizontal">Horizontal</a></li>
                        </ul>
                    </div>
                </div>
                <hr class="border-1 text-white my-2">
                <div class="row mx-0 mb-2">
                    <button id="zoomIn" type="button" class="btn btn-outline-dark waves-effect text-white border-0 px-1"><i class="ti ti-zoom-in fs-3"></i></button>
                </div>
                <div class="row mx-0">
                    <button id="zoomOut" type="button" class="btn btn-outline-dark waves-effect text-white border-0 px-1"><i class="ti ti-zoom-out fs-3"></i></button>
                </div>
            </div>
            <div id="graph"></div>
        </div>
    </div>

@endsection
