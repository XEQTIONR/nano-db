/** doughnut.js
* @desc Builds the doughnut based on data
Depends on d3.js
*/
function build(value, total, outformat,prefix ,ratio_flag ,div)
{
var duration = 1500,
    transition = 200,
    //value = 70, //
    width = document.getElementsByClassName('stat-diagram')[0].clientHeight,
    height = document.getElementsByClassName('stat-diagram')[0].clientHeight;

var dataset = {
            lower: [0,value],//calcPercent(0), [lowest, score]
            upper: [value,total-value]//calcPercent(percent) [score, max-score]
        },
        radius = Math.min(width, height) / 3,
        pie = d3.layout.pie().sort(null),
        format = d3.format(outformat); // 4.1%" format can do percentage as well

var arc = d3.svg.arc()   // radii of inner and outer circle
        .innerRadius(radius * .7)
        .outerRadius(radius);

var svg = d3.select(div).append("svg") //append to a DOM element
        .attr("width", width)
        .attr("height", height)
        .append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

var path = svg.selectAll("path")
                .data(pie(dataset.lower))
                .enter().append("path")
                .attr("class", function (d, i) {
                    return "color" + i
                })
                .attr("d", arc)
                .each(function (d) {
                    this._current = d;
                });

var text = svg.append("text")
        .attr("text-anchor", "middle")
        .attr("dy", ".3em");

var progress = 0;

var timeout = setTimeout(function () {
    clearTimeout(timeout);
    path = path.data(pie(dataset.upper));
    path.transition().duration(duration).attrTween("d", function (a) {
        var i = d3.interpolate(this._current, a);
        var i2 = d3.interpolate(progress, value)
        this._current = i(0);
        return function (t) {
          if(ratio_flag)
            text.text(prefix+format(i2(t)/100)); // /100 creates precentage
          else
            text.text(prefix+format(i2(t)));
            return arc(i(t));
        };
    });
}, 200);
}
