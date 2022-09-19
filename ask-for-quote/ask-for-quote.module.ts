import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AskforQuoteComponent } from './ask-for-quote.component';
import { AskforQuoteDetailComponent } from './ask-for-quote-detail/ask-for-quote-detail.component';
import { AskforQuoteRoutingModule } from './ask-for-quote-routing.module';
import { MatCardModule } from '@angular/material/card';
import { SpinnerModule } from 'app/components/spinner/spinner.module';
import { RouterModule } from '@angular/router';
import { AskforQuoteService } from 'app/core/services/askfor-quote.service';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

@NgModule({
    imports:[
        CommonModule,
        AskforQuoteRoutingModule,
        MatCardModule,
        SpinnerModule,
        RouterModule,
        FormsModule,
        ReactiveFormsModule
    ],
    declarations:[
        AskforQuoteComponent,
        AskforQuoteDetailComponent
    ],
    providers:[
        AskforQuoteService
    ]
})

export class AskforQuoteModule {

}