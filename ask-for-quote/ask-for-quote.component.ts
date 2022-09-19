import { Component, OnInit } from "@angular/core";
import { Router, ActivatedRoute, ParamMap } from '@angular/router';
import { AskforQuoteService } from 'app/core/services/askfor-quote.service';



@Component({
    selector:"ask-for-quote",
    templateUrl:"ask-for-quote.component.html",
    styleUrls:["ask-for-quote.component.scss"]
})

export class AskforQuoteComponent implements OnInit{
    company_id;
    askForQuotes = [];
    isLoading : boolean = false;
    is_complete=0;
    currentWholeUrl;

    constructor(
        private askForQuoteService : AskforQuoteService,
        private router: Router,
        private route: ActivatedRoute,
    ){ 
        this.isLoading = true;
        this.company_id  = this.route.snapshot.paramMap.get('cid');
        this.route.queryParams.subscribe(res => {
            this.askForQuotes=res.data;
            console.log(res);
        })
    }

    ngOnInit(){
        console.log(123123);
        this.isLoading = true;
        this.browse();
        //check router is complete or incomplete
        this.currentWholeUrl = document.URL; 
        console.log(this.currentWholeUrl);

        if(this.currentWholeUrl.includes("incomplete")){
            this.is_complete=0;
            this.browse();
        }else if(this.currentWholeUrl.includes("complete")){
            this.is_complete=1;
            this.browse();
        }
    }

    browse(){
        this.askForQuoteService.browse(this.company_id, this.is_complete).subscribe(
            res=>{
                this.askForQuotes = res.data;
                this.isLoading = false;
                console.log(this.askForQuotes);
            }
        )
    }

}