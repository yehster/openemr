/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function find_stats_column()
{
    var first_header=$("div.section-header-dynamic:first");
    return first_header.parent();
}