/*global $,vote_history_url,vote_history_key */
$(function(){
	var $vote_history=$('#vote_history'),change_key=vote_history_key+'UsersChange',user_key=vote_history_key+'User',
		upvotes=[],emptyvotes=[],downvotes=[];
	var vote_sum={upvotes:0,emptyvotes:0,downvotes:0};
	var user_vote={},start_date;
	$vote_history.css('height','400px');
	var user_cache={};

	function toolTip(tooltipData) {
		var toolTipContent = '';
		var dataPoint,change,user,color;
		dataPoint = tooltipData.entries[0].dataPoint;
		change = dataPoint.data[change_key];
		if(dataPoint.data.User) {
			var hash = dataPoint.data.User.profile_url.split('/').pop();
			if(user_cache[hash]) {
				user=user_cache[hash];
			} else {
				user=user_cache[hash]=$.ajax({
					'async':false,
					'dataType':'json',
					'url':dataPoint.data.User.profile_url+'.json'
				}).responseJSON;
			}
		}
		color = tooltipData.entries[0].dataSeries.color;
		toolTipContent += 'Data: '+change.created.toLocaleString()+'<br />';
		toolTipContent += 'Łaczna liczba głosów: '+(change.upvotes+change.emptyvotes+change.downvotes)+'<br />';
		toolTipContent += 'Głosy poparcia (+1): '+change.upvotes+'<br />';
		if(emptyvotes.length) toolTipContent += 'Głosy anulowane (0): '+change.emptyvotes+'<br />';
		if(downvotes.length) toolTipContent += 'Głosy braku poparcia (-1): '+change.downvotes+'<br />';

		toolTipContent += '<span style="color: '+color+';">';
		if(user) {
			toolTipContent += 'Użytkownik <a target="_blank" href="'+dataPoint.data.User.profile_url+'">';
			toolTipContent += '<img width="16" height="16" src="'+(user.User.picture_url+'').replace('ROZMIAR_OBRAZKA',16)+'">';
			if(user.ConnectedRepresentative.id) {
				toolTipContent += escapeHtml(user.ConnectedRepresentative.firstname+' '+user.ConnectedRepresentative.lastname);
			} else {
				toolTipContent += escapeHtml(user.User.username);
			}
			toolTipContent += '</a>';
			if(typeof change.old_value==='undefined') {
				if(change.value!==0) {
					toolTipContent += ' oddał głos '
				} else {
					toolTipContent += ' oddał pusty głos'
				}
			} else {
				if(change.value!==0) {
					toolTipContent += ' zmienił głos z ';
				} else {
					toolTipContent += ' anulował głos '
				}

				if(change.old_value===-1) {
					toolTipContent += 'brak poparcia (-1)';
				}
				if(change.old_value===0) {
					toolTipContent += 'anulowany (0)';
				}
				if(change.old_value===1) {
					toolTipContent += 'poparcie (+1)';
				}
				if(change.value!==0) {
					toolTipContent+=' na ';
				}
			}
			if(change.value===-1) {
				toolTipContent += 'brak poparcia (-1)';
			}
			if(change.value===1) {
				toolTipContent += 'poparcie (+1)';
			}
			toolTipContent += '</span>';
			if(change.emaildomain) {
				toolTipContent += '<br />';
				toolTipContent += 'Email: ••••@'+escapeHtml(change.emaildomain);
			}
			if(change.ip) {
				toolTipContent += '<br />';
				if(change.hostname) {
					toolTipContent += 'IP głosu: '+escapeHtml(change.hostname)+' ('+escapeHtml(change.ip)+')';
				} else {
					toolTipContent += 'IP głosu: '+escapeHtml(change.ip);
				}
			}
		}
		return toolTipContent;
	}

	function renderChart() {
		var chart_data={
			zoomEnabled: true,
			panEnabled: true,
			title: {
				text: "Historia głosowania"
			},
			legend: {
				cursor: 'pointer',
				itemclick: function(e) {
					e.dataSeries.visible= !(typeof(e.dataSeries.visible)==='undefined' || e.dataSeries.visible);
					$vote_history.CanvasJSChart().render();
				}
			},
			axisX: {
			},
			axisY: {
				includeZero: true
			},
			data: [
				{
					name: 'Głosy poparcia (+1)',
					type: "stepLine",
					showInLegend: true,
					dataPoints: upvotes
				},
				{
					name: 'Głosy braku poparcia (-1)',
					type: "stepLine",
					showInLegend: !!downvotes.length,
					dataPoints: downvotes
				},
				{
					name: 'Głosy anulowane (0)',
					type: "stepLine",
					showInLegend: !!emptyvotes.length,
					dataPoints: emptyvotes
				}
			],
			toolTip: {
				content: toolTip
			}
		};
		$vote_history.CanvasJSChart(chart_data);
	}

	function updateChartData(response) {
		var data = response.data,extra_data;
		if(!response.pagination.has_prev_page) {
			tmp = data[0][change_key].created.split(/[- :]/);
			start_date = new Date(tmp[0],tmp[1]-1,tmp[2],tmp[3]);
		}
		if(!response.pagination.has_next_page) {
			extra_data ={};
			extra_data[change_key]={};
			extra_data[change_key].created=new Date();
			extra_data[change_key].upvotes=vote_sum.upvotes;
			extra_data[change_key].emptyvotes=vote_sum.emptyvotes;
			extra_data[change_key].downvotes=vote_sum.downvotes;
			data.push(extra_data);
		}
		var dfd = $.Deferred(),data_len = data.length,change,user,tmp;
		for(var i=0;i<data_len;i++) {
			change = data[i][change_key];
			user = data[i]['User'];

			data[i][change_key].upvotes=vote_sum.upvotes;
			data[i][change_key].emptyvotes=vote_sum.emptyvotes;
			data[i][change_key].downvotes=vote_sum.downvotes;

			if(typeof data[i][change_key].value!=='undefined') {
				tmp = change.created.split(/[- :]/);
				data[i][change_key].created = new Date(tmp[0],tmp[1]-1,tmp[2],tmp[3],tmp[4],tmp[5]);

				data[i][change_key].value = parseInt(change.value,10);
				data[i][change_key].old_value = user_vote[data[i][user_key].user_id];
				user_vote[data[i][user_key].user_id] = change.value;

				if(change.old_value===1) {
					vote_sum.upvotes--;
				}
				if(change.old_value===0) {
					vote_sum.emptyvotes--;
				}
				if(change.old_value===-1) {
					vote_sum.downvotes--;
				}
				if(change.value===1) {
					vote_sum.upvotes++;
				}
				if(change.value===0) {
					vote_sum.emptyvotes++;
				}
				if(change.value===-1) {
					vote_sum.downvotes++;
				}
				if(change.old_value!==change.value) {
					if(change.old_value===1) {
						upvotes.push({x:change.created,y:vote_sum.upvotes,data:data[i]});
					}
					if(change.old_value===0) {
						emptyvotes.push({x:change.created,y:vote_sum.emptyvotes,data:data[i]});
					}
					if(change.old_value===-1) {
						downvotes.push({x:change.created,y:vote_sum.downvotes,data:data[i]});
					}
				}

				if(change.value===1) {
					upvotes.push({x:change.created,y:vote_sum.upvotes,data:data[i]});
				}
				if(change.value===0) {
					emptyvotes.push({x:change.created,y:vote_sum.emptyvotes,data:data[i]});
				}
				if(change.value===-1) {
					downvotes.push({x:change.created,y:vote_sum.downvotes,data:data[i]});
				}
			} else {
				upvotes.push({x:change.created,y:vote_sum.upvotes,data:data[i]});
				if(emptyvotes.length) {
					emptyvotes.push({x:change.created,y:vote_sum.emptyvotes,data:data[i]});
				}
				if(downvotes.length) {
					downvotes.push({x:change.created,y:vote_sum.downvotes,data:data[i]});
				}

			}

		}
		if(!response.pagination.has_next_page && start_date) {
			extra_data ={};
			extra_data[change_key]={};
			extra_data[change_key].created=start_date;
			extra_data[change_key].upvotes=0;
			extra_data[change_key].emptyvotes=0;
			extra_data[change_key].downvotes=0;

			upvotes.unshift({x:start_date,y:0,data:extra_data});
			if(emptyvotes.length) {
				emptyvotes.unshift({x:start_date,y:0,data:extra_data});
			}
			if(downvotes.length) {
				downvotes.unshift({x:start_date,y:0,data:extra_data});
			}

		}
		dfd.resolve();
		return dfd.promise();
	}

	function getVotes(page) {
		var dfd = $.Deferred();
		var params = page?{page:page,limit:1000}:{};
		$.getJSON(vote_history_url,params).success(function(response){
			if(response && response.success) {
				if(response.pagination.has_next_page) {
					dfd.notify();
					var next_page_votes = getVotes(response.pagination.current_page+1);
					$.when(next_page_votes,updateChartData(response)).done(dfd.resolve).fail(dfd.reject).progress(dfd.notify);
				} else {
					$.when(updateChartData(response,true)).done(dfd.resolve).fail(dfd.reject);
				}
			}
		}).error(dfd.reject);
		return dfd.promise();
	}
	getVotes().done(renderChart).progress(renderChart);
});