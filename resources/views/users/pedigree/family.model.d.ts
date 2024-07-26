export interface FamilyModel {
    id: string;
    personId: number;
    label1: string;
    label2: string;
    name: string;
    gender: string;
    photo: string;
    parentId: string;
    personOrder?: number;
    childOrder: number; // currently not used in UI
    size?: number;
    spouseId?: number;
    spouseName?: string;
    spouseLabel1: string;
    spouseGender?: string;
    spouseOrder?: number; // currently not used in UI
    spouseDrillTo: boolean;
    primarySpouseId?: string; // used in multiple spouses for the second spouse
    spousePhoto: string;
    path?: string;
  }