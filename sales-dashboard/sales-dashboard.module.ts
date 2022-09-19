import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SalesDashboardComponent } from './sales-dashboard.component';
import { SalesDashboardDetailComponent } from './sales-dashboard-detail/sales-dashboard-detail.component';
import { SalesDashboardRoutingModule } from './sales-dashboard-routing.module';
import { MatCardModule } from '@angular/material/card';
import { SpinnerModule } from 'app/components/spinner/spinner.module';
import { RouterModule } from '@angular/router';
import { SalesDashboardService } from 'app/core/services/sales-dashboard.service';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { DateFilterModule } from 'app/components/date-filter/date-filter.module';

@NgModule({
    imports:[
        CommonModule,
        SalesDashboardRoutingModule,
        MatCardModule,
        SpinnerModule,
        RouterModule,
        FormsModule,
        ReactiveFormsModule,
        DateFilterModule,
        
    ],
    declarations:[
        SalesDashboardComponent,
        SalesDashboardDetailComponent,
    ],
    providers:[
        SalesDashboardService
    ]
})

export class SalesDashboardModule {

}