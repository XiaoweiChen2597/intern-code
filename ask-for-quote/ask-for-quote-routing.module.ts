import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AskforQuoteComponent } from './ask-for-quote.component';
import { AskforQuoteDetailComponent } from './ask-for-quote-detail/ask-for-quote-detail.component';
import { AskForQuoteHeader, AskForQuoteDetailHeader } from "app/core/models/header";


const AskforQuoteRoutes: Routes =[
    {
        path:'',
        component:AskforQuoteComponent,
        data:AskForQuoteHeader
    },
    {
        path:"detail/:afqId",
        component:AskforQuoteDetailComponent,
        data: AskForQuoteDetailHeader
    },
    {
        path:"incomplete",
        component:AskforQuoteComponent,
        data: AskForQuoteHeader
    },
    {
        path:"complete",
        component:AskforQuoteComponent,
        data: AskForQuoteHeader
    },
    {
        path:"incomplete/detail/:afqId",
        component:AskforQuoteDetailComponent,
        data: AskForQuoteDetailHeader
    },
    {
        path:"complete/detail/:afqId",
        component:AskforQuoteDetailComponent,
        data: AskForQuoteDetailHeader
    },
]

@NgModule({
    imports:[
        RouterModule.forChild(AskforQuoteRoutes)
    ],
    exports:[
        RouterModule
    ],
    providers:[

    ]
})

export class AskforQuoteRoutingModule{

}

