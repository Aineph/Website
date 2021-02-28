import {Controller} from 'stimulus';
import 'chart.js';

export default class extends Controller {
    connect() {
        let dataType = this.element.getAttribute('data-type');
        let backgroundColors = this.element.getAttribute('data-colors');
        let options = {}

        if (backgroundColors.includes(';')) {
            backgroundColors = backgroundColors.split(';');
        }
        if (dataType === 'bar') {
            options = {
                scales:
                    {
                        yAxes:
                            [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                    }
            }
        } else if (dataType === 'radar') {
            options = {
                scale: {
                    ticks: {
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                }
            }
        }
        this.chart = new Chart(this.element, {
            type: dataType,
            data: {
                labels: this.element.getAttribute('data-labels').split(';'),
                datasets: [{
                    label: this.element.getAttribute('data-title'),
                    data: this.element.getAttribute('data-values').split(';'),
                    backgroundColor: backgroundColors
                }]
            },
            options: options
        });
    }
}
