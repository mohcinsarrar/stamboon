        const nodeWidth = 150;
        const nodeWidthSpouse = 320;
        const gapWidth = nodeWidthSpouse - nodeWidth * 2;
        const linkShift = Math.round((nodeWidth + gapWidth) / 2);
        const nodeHeight = 40;
        const nodeHeightSpouse = 40;
        const maleIcon = '/storage/portraits/male.png';
        const femaleIcon = '/storage/portraits/female.png';

        var chart;

        var data = getTreeData();

        chart = new d3.OrgChart()
            .container("#graph")
            .data(data)
            .compact(false)
            .rootMargin(100)
            .nodeWidth((d) =>
                d.data.spouseId !== undefined ? nodeWidthSpouse : nodeWidth
            )
            .nodeHeight((d) =>
                d.data.spouseId !== undefined ? nodeHeightSpouse : nodeHeight
            )
            .childrenMargin((d) => 75)
            .siblingsMargin((d) => 30)
            .linkUpdate(function(d, i, arr) {
                // drawing the connecting line
                d3.select(this)
                    .attr('stroke', (d) => 'lightgray')
                    .attr('stroke-width', (d) => 2);
                //.attr('stroke-dasharray', '4,4');
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

        chart.render();


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
                        <div class="col ${personCssClass} person-member person-${person.id}"  onclick="window.selectedPersonId = '${person.id}';">
                            <div class="col-grow">
                            <img src="${personIcon}" class="person-icon" />
                        </div>
                        <div class="col person-name">${person.name}</div>
                        </div>`;
                return nodeContent;
            }
        }

        function getTreeData() {
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
                {
                    id: 'L1C3',
                    name: 'Son 2 Kumar',
                    gender: 'M',
                    parentId: 'L1',
                    spouseId: 'L1C3S',
                    spouseName: 'Daughter in law',
                    spouseGender: 'F',
                },
                {
                    id: 'L1C1C1',
                    name: 'Grand Daughter 1',
                    gender: 'F',
                    parentId: 'L1C1'
                },
                {
                    id: 'L1C1C2',
                    name: 'Grand Son 1',
                    gender: 'M',
                    parentId: 'L1C1'
                },
                {
                    id: 'L1C2C1',
                    name: 'Grand Daughter 2',
                    gender: 'F',
                    parentId: 'L1C2'
                },
                {
                    id: 'L1C2C2',
                    name: 'Grand Daughter 3',
                    gender: 'F',
                    parentId: 'L1C2'
                },
                {
                    id: 'L1C3C1',
                    name: 'Grand Son 2',
                    gender: 'M',
                    parentId: 'L1C3'
                },
                {
                    id: 'L1C3C2',
                    name: 'Grand Son 3',
                    gender: 'M',
                    parentId: 'L1C3'
                },
            ];

            return treeData;
        }
