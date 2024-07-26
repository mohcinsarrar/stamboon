<template>
    <div class="chart-container" style="width: 100%; min-height: 100%"></div>
  </template>
  <style lang="scss">
  .person-member {
    display: flex;
    flex-wrap: wrap;
    height: 40px;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.24);
  }
  
  .male-member {
    background: radial-gradient(circle, #b0e0e6 0%, #00ced1 100%);
  }
  .female-member {
    background: radial-gradient(circle, #ffb6c1 0%, #ff69b4 100%);
  }
  
  .female-spouse {
    margin-left: -15px;
  }
  
  .male-spouse {
    margin-right: -15px;
  }
  
  div.line {
    margin-top: 13px;
    width: 5px;
  }
  div.line hr {
    border-style: solid;
    border-width: 2px 0 0;
  }
  
  .person-box {
    display: flex;
    height: 40px;
    align-items: center;
    width: 100%;
  }
  
  .person-icon {
    width: 40px;
    height: 40px;
  }
  
  .person-icon-div {
    width: 40px;
    height: 40px;
  }
  
  .person-name {
    padding-left: 1px;
    text-align: left;
    justify-content: center;
  }
  
  .selected-person {
    height: 42px;
    border: 3px solid #152785;
  }
  
  .view-person {
    position: relative;
    left: -8px;
    top: -44px;
    color: black;
    text-decoration: none;
    font-size: 10px;
    background-color: white;
    width: 20px;
  }
  
  .rotate {
    transform: rotate(-45deg);
    -webkit-transform: rotate(-45deg);
    -moz-transform: rotate(-45deg);
    -ms-transform: rotate(-45deg);
    -o-transform: rotate(-45deg);
    filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=1);
  }
  
  .profile-photo {
    border-radius: 50%;
  }
  
  .drill-to {
    position: relative;
    left: 125px;
    top: -35px;
    font-size: 25px;
  }
  .hide {
    display: none;
  }
  </style>
  
  <script setup lang="ts">
  import * as d3 from 'd3';
  import { onMounted, watch } from 'vue';
  import { useQuasar } from 'quasar';
  
  import { FamilyModel } from 'src/core/models/family.model';
  import { UserProfileModel } from 'src/core/models/user/user-profile.model';
  import maleIcon from 'src/assets/images/male.svg';
  import femaleIcon from 'src/assets/images/female.svg';
  import treeConfiguration from 'src/core/services/tree-configuration';
  import { OrgChart, Connection, NodeId } from 'd3-org-chart';
  import { HierarchyNode } from 'd3-hierarchy';
  import session1 from 'src/core/services/session';
  import { constants } from 'src/core/common/constants';
  import { PersonService } from 'src/core/services/person.service';
  // import PersonDetailsDialog from 'src/components/PersonDetailsDialog.vue';
  import { eventBus } from 'src/core/common/event-bus';
  import { RelatedPersonModel } from 'src/core/models/related-person.model';
  import { useSessionStore } from 'src/stores/session.store';
  import { useRouter } from 'vue-router';
  
  const session = useSessionStore();
  const router = useRouter();
  
  // class variables
  let chart: OrgChart<FamilyModel>;
  let selectedPersonId: number;
  let loadTreePersonId = 1; // this is the id used to loadTree
  let prevSelectedPersonId: number;
  let familyData: FamilyModel[] = [];
  
  const $q = useQuasar();
  
  onMounted(async () => {
    await loadTree(session.userProfile.personId);
  });
  
  // event bus related events
  eventBus.on('search-person', (personId: number) => {
    loadTree(personId);
  });
  
  eventBus.on('related-persons', (relatedPerson: RelatedPersonModel) => {
    loadRelatedTree(relatedPerson.firstPersonId, relatedPerson.relatedPersonId);
  });
  
  watch([session1.refreshDetails], ([newRefresh], [oldRefesh]) => {
    // refresh
    if (oldRefesh !== newRefresh) {
      if (newRefresh) {
        refresh();
        // wait for 100ms to rest the refreshDetails so that watch triggers in all the screens
        setTimeout(() => {
          session1.refreshDetails.value = false;
        }, 100);
      }
    }
  });
  
  async function loadRelatedTree(firstPersonId: number, relatedPersonId: number) {
    selectedPersonId = -1;
    familyData = await PersonService.getRelationship(
      firstPersonId,
      relatedPersonId
    );
    d3.select('.svg-chart-container').remove();
    renderChart(familyData);
    prevSelectedPersonId = -1; // always highlight related person
    selectRelatedNodes(firstPersonId, relatedPersonId);
  }
  
  function selectRelatedNodes(firstPersonId: number, relatedPersonId: number) {
    let personElement = document.getElementsByClassName(
      `person-${firstPersonId}`
    )[0];
    personElement.classList.add('selected-person');
    personElement = document.getElementsByClassName(
      `person-${relatedPersonId}`
    )[0];
    personElement.classList.add('selected-person');
  }
  
  async function loadTree(
    personId: number,
    refreshPersonId: number | undefined = undefined
  ) {
    if (refreshPersonId !== undefined) selectedPersonId = refreshPersonId;
    else selectedPersonId = personId;
    loadTreePersonId = personId;
    familyData = await PersonService.getHierarchy(personId);
    d3.select('.svg-chart-container').remove();
    renderChart(familyData);
    prevSelectedPersonId = selectedPersonId;
  }
  
  function renderChart(data: FamilyModel[]) {
    chart = new OrgChart<FamilyModel>()
      .container(treeConfiguration.chartContainer)
      .data(data)
      //.layout('left')
      .onNodeClick((nodeId: NodeId) => nodeClicked(nodeId))
      .rootMargin(treeConfiguration.rootMargin)
      .nodeWidth((d: HierarchyNode<FamilyModel>) => {
        if (d.data.id === constants.rootId) return 0;
        if (d.data.primarySpouseId !== undefined) {
          return treeConfiguration.nodeWidth;
        }
        return d.data.spouseId !== undefined
          ? treeConfiguration.nodeWidthSpouse
          : treeConfiguration.nodeWidth;
      })
  
      .nodeHeight((d: HierarchyNode<FamilyModel>) => {
        if (d.data.id === constants.rootId) return 0;
        else
          return d.data.spouseId !== undefined
            ? treeConfiguration.nodeHeightSpouse
            : treeConfiguration.nodeHeight;
      })
      .childrenMargin(() => treeConfiguration.childrenMargin)
      .siblingsMargin(() => treeConfiguration.siblingsMargin)
      .neightbourMargin(() => treeConfiguration.neightbourMargin)
      .linkUpdate(function (d: HierarchyNode<FamilyModel>) {
        // drawing the connecting line
        if (
          d.data.parentId === constants.rootId ||
          d.data.primarySpouseId !== undefined
        ) {
          return;
        } else {
          d3.select(this)
            .attr('stroke', () => treeConfiguration.linkStroke)
            .attr('stroke-width', () => treeConfiguration.linkStrokeWidth);
        }
      })
      .connectionsUpdate(function () {
        d3.select(this)
          .attr('stroke', () => treeConfiguration.connectionStroke)
          .attr('stroke-width', () => treeConfiguration.connectionStrokeWidth)
          .lower();
      })
      .nodeContent(function (d: HierarchyNode<FamilyModel>) {
        const personData: FamilyModel = <FamilyModel>d.data;
  
        let extraCss = '';
        if (personData.primarySpouseId !== undefined) {
          if (personData.gender === 'M') {
            extraCss = 'female-spouse';
          } else {
            extraCss = 'male-spouse';
          }
        }
  
        let nodeHtml = `<div class="row ${extraCss}">`;
        if (personData.primarySpouseId !== undefined) {
          // additional spouses
          nodeHtml += getPersonNodeContent(personData, 'spouse');
        } else if (personData.gender === 'F') {
          nodeHtml += getPersonNodeContent(personData, 'spouse');
          nodeHtml += getPersonNodeContent(personData, 'person');
        } else {
          nodeHtml += getPersonNodeContent(personData, 'person');
          nodeHtml += getPersonNodeContent(personData, 'spouse');
        }
        nodeHtml += '</div>';
  
        return nodeHtml;
      })
      .compact(false);
  
    // changing the links for persons who has spouse
    chart.layoutBindings().top.linkX = (d: HierarchyNode<FamilyModel>) => {
      let x = d.x;
      if (d.data === undefined) {
        // Using x & y locations get the corresponding person data
        const allNodes = chart.getChartState().allNodes;
        allNodes.forEach((node) => {
          if (node.x === d.x && node.y === d.y) {
            if (node.data.gender === 'M') {
              x = d.x + d.width / 2;
            } else {
              x = d.x - d.width / 2;
            }
          }
        });
      } else if (d.data.spouseId !== undefined && d.data.gender === 'M') {
        x = d.x - treeConfiguration.linkShift; // for parent to child link
      } else if (d.data.spouseId !== undefined && d.data.gender === 'F') {
        x = d.x + treeConfiguration.linkShift; // for parent to child link
      } else {
        x = d.x;
      }
  
      return x;
    };
  
    chart.layoutBindings().top.linkY = (d: HierarchyNode<FamilyModel>) => {
      if (d.data === undefined) {
        // connections
        return d.y + d.height / 2;
      } else {
        return d.y;
      }
    };
  
    chart.layoutBindings().top.linkJoinX = (d: HierarchyNode<FamilyModel>) => {
      let x = d.x;
      if (d.data === undefined) {
        // connections
        // Using x & y locations get the corresponding person data
        const allNodes = chart.getChartState().allNodes;
        allNodes.forEach((node) => {
          if (node.x === d.x && node.y === d.y) {
            if (node.data.gender === 'M') {
              x = d.x - d.width / 2 - 15;
            } else {
              x = d.x + d.width / 2 + 15;
            }
          }
        });
      } else {
        x = d.x;
      }
  
      return x;
    };
    chart.layoutBindings().top.linkJoinY = (d: HierarchyNode<FamilyModel>) => {
      if (d.data === undefined) {
        // connections
        return d.y + d.height / 2;
      } else {
        return d.y;
      }
    };
  
    // checking for multiple spouses
    const multipleSpouseConnections: Connection[] = [];
    data.forEach((model) => {
      if (model.primarySpouseId !== undefined) {
        multipleSpouseConnections.push({
          from: model.primarySpouseId,
          to: model.id,
          label: '',
        });
      }
    });
    chart.connections(multipleSpouseConnections);
  
    chart.render();
  
    // getting the selectedPerons to show
    const selectedPersons = familyData.filter(
      (p) => p.personId === selectedPersonId
    );
    if (selectedPersons.length > 0) {
      const nodeId: string = selectedPersons[0].id;
      chart.setCentered(nodeId);
    }
    chart.expandAll();
  
    // hiding the root node
    const rootIdNode = document.querySelector(
      '.person-' + constants.rootPersonId
    );
    const parentRootIdNode = rootIdNode?.closest('.node');
    if (parentRootIdNode !== undefined && parentRootIdNode !== null)
      parentRootIdNode.style.display = 'none';
  }
  
  function getPersonNodeContent(personData: FamilyModel, personType: string) {
    const person: UserProfileModel = <UserProfileModel>{};
  
    if (personType === 'spouse') {
      if (personData.spouseId !== undefined) {
        person.personId = personData.spouseId;
      } else {
        return '';
      }
      person.personName = personData.spouseLabel1;
      person.gender = personData.spouseGender;
      person.photo = personData.spousePhoto;
    } else {
      person.personId = personData.personId;
      person.personName = personData.label1;
      person.gender = personData.gender;
      person.photo = personData.photo;
    }
  
    let personCssClass, personIcon;
    let photoClass = '';
    if (person.gender === 'M') {
      personCssClass = 'male-member';
      personIcon = maleIcon;
    } else {
      personCssClass = 'female-member';
      personIcon = femaleIcon;
    }
  
    if (person.photo !== undefined && person.photo !== null) {
      personIcon = person.photo;
      photoClass = 'profile-photo';
    }
  
    let nodeContent = '';
    if (
      personData.spouseId !== undefined &&
      person.gender === 'F' &&
      personData.primarySpouseId === undefined
    ) {
      nodeContent += '<div class="line"><hr/></div>';
    }
  
    let selectedPersonCssClass = '';
    if (selectedPersonId === person.personId) {
      selectedPersonCssClass = 'selected-person';
    }
  
    let drillToHide = 'hide';
    if (personType === 'spouse' && personData.spouseDrillTo) {
      drillToHide = ''; // hide drill to icon
    }
  
    nodeContent += `
          <div class="col ${personCssClass} person-member person-${person.personId} ${selectedPersonCssClass}">
            <div class="person-box" onclick="window.selectedPersonId=${person.personId};">
              <div class="person-icon-div" >
                <div onclick="window.viewPersonId=${person.personId};">
                  <img src="${personIcon}" class="person-icon ${photoClass}" />
                  <div class="view-person rotate">view</div>
                </div>
                <i class="fa-solid fa-street-view drill-to ${drillToHide}"></i>
              </div>
              <div class="col person-name">${person.personName}</div>
            </div>
          </div>`;
    return nodeContent;
    //}
  }
  
  function nodeClicked(id: NodeId) {
    // highlight the user clicked node
    highlightSelectedNode();
  
    if (window.viewPersonId != undefined) {
      showDetails(window.viewPersonId);
      window.viewPersonId = undefined;
    } else {
      const person = familyData.filter((p) => p.id === id)[0];
      if (person.spouseId === window.selectedPersonId && person.spouseDrillTo) {
        loadTree(person.spouseId);
      }
    }
  }
  
  function highlightSelectedNode() {
    // remove prev selected person class and selecting currnet one
    let personElement;
    if (prevSelectedPersonId > 0) {
      personElement = document.getElementsByClassName(
        `person-${prevSelectedPersonId}`
      )[0];
      personElement.classList.remove('selected-person');
    }
  
    selectedPersonId = window.selectedPersonId;
    personElement = document.getElementsByClassName(
      `person-${selectedPersonId}`
    )[0];
    personElement.classList.add('selected-person');
    prevSelectedPersonId = selectedPersonId;
  }
  
  function showDetails(viewPersonId: number) {
    router.push({
      name: 'person-detail',
      params: {
        detailPersonId: viewPersonId,
      },
    });
  }
  
  function refresh() {
    loadTree(loadTreePersonId, session1.refreshPersonId);
    session1.refreshPersonId = -1;
  }
  </script>