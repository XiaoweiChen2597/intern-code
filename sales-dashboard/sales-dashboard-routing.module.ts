import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { SalesDashboardComponent } from './sales-dashboard.component';
import { SalesDashboardDetailComponent } from './sales-dashboard-detail/sales-dashboard-detail.component';
import { SalesDashboardHeader, SalesDashboardDetailHeader} from "app/core/models/header";

const SalesDashboardRoute: Routes =[
    {
        path:"",
        component:SalesDashboardComponent,
        data:SalesDashboardHeader
    },
    {
        path:"quote",
        component:SalesDashboardComponent,
        data:SalesDashboardHeader
    },
    {
        path:"salesorder",
        component:SalesDashboardComponent,
        data:SalesDashboardHeader
    },
    {
        path:"invoice",
        component:SalesDashboardComponent,
        data:SalesDashboardHeader
    },
    {
        path:"detail/:afqId",
        component:SalesDashboardDetailComponent,
        data:SalesDashboardDetailHeader
    },
    {
        path:"quote/detail/:afqId",
        component:SalesDashboardDetailComponent,
        data:SalesDashboardDetailHeader
    },
    {
        path:"salesorder/detail/:afqId",
        component:SalesDashboardDetailComponent,
        data:SalesDashboardDetailHeader
    },
    {
        path:"invoice/detail/:afqId",
        component:SalesDashboardDetailComponent,
        data:SalesDashboardDetailHeader
    },
]

@NgModule({
    imports:[
        RouterModule.forChild(SalesDashboardRoute)
    ],
    exports:[
        RouterModule
    ],
    providers:[

    ]
})

export class SalesDashboardRoutingModule{

}

