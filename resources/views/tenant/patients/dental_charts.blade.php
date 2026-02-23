<div class="card bg-base-100 shadow-sm border border-base-200">
    <div class="card-body overflow-x-auto">
        <h3 class="card-title text-lg font-bold mb-6 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            Interactive Adult Dental Chart
        </h3>

        <div id="dental-chart" class="p-4 bg-white rounded-2xl border border-base-200 shadow-inner">
            <table class="mx-auto border-separate border-spacing-x-1 border-spacing-y-2">
                <tbody>
                    <!-- Upper Arch Tooth Numbers -->
                    <tr class="text-[10px] font-bold text-base-content/40">
                        <td>18</td><td>17</td><td>16</td><td>15</td><td>14</td><td>13</td><td>12</td><td>11</td>
                        <td>21</td><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td><td>27</td><td>28</td>
                    </tr>
                    <!-- Upper Arch SVGs -->
                    <tr>
                        @foreach(['molar', 'molar', 'molar', 'premolar-5', 'premolar-4', 'incisor', 'incisor', 'incisor'] as $type)
                        <td>
                            <svg width="24" height="40" transform="scale(1,-1)" class="{{ $type }} cursor-pointer transition-all duration-200 hover:opacity-80">
                                @if($type === 'molar')
                                    <polygon id="top" points="0,0 24,0 16,8 8,8" class="polygon unmarked" />
                                    <polygon id="left" points="0,0 8,8 8,16 0,24" class="polygon unmarked" />
                                    <polygon id="bottom" points="0,24 8,16 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="right" points="24,0 16,8 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="center" points="8,8, 16,8 16,16 8,16" class="polygon unmarked" />
                                    <polygon id="root-1" points="0,24, 4,40 8,24" class="polygon unmarked" />
                                    <polygon id="root-2" points="8,24, 12,40 16,24" class="polygon unmarked" />
                                    <polygon id="root-3" points="16,24, 20,40 24,24" class="polygon unmarked" />
                                @elseif($type === 'premolar-5')
                                    <polygon id="top" points="0,0 24,0 16,8 8,8" class="polygon unmarked" />
                                    <polygon id="left" points="0,0 8,8 8,16 0,24" class="polygon unmarked" />
                                    <polygon id="bottom" points="0,24 8,16 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="right" points="24,0 16,8 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="center" points="8,8, 16,8 16,16 8,16" class="polygon unmarked" />
                                    <polygon id="root-2" points="8,24, 12,40 16,24" class="polygon unmarked" />
                                @elseif($type === 'premolar-4')
                                    <polygon id="top" points="0,0 24,0 16,8 8,8" class="polygon unmarked" />
                                    <polygon id="left" points="0,0 8,8 8,16 0,24" class="polygon unmarked" />
                                    <polygon id="bottom" points="0,24 8,16 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="right" points="24,0 16,8 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="center" points="8,8, 16,8 16,16 8,16" class="polygon unmarked" />
                                    <polygon id="root-1" points="0,24, 4,40 8,24" class="polygon unmarked" />
                                    <polygon id="root-3" points="16,24, 20,40 24,24" class="polygon unmarked" />
                                @elseif($type === 'incisor')
                                    <polygon id="top" points="8,0 16,0 16,12 8,12" class="polygon unmarked" />
                                    <polygon id="left" points="0,8 8,8 8,16 0,16" class="polygon unmarked" />
                                    <polygon id="bottom" points="8,12 16,12 16,24 8,24" class="polygon unmarked" />
                                    <polygon id="right" points="16,8 24,8 24,16 16,16" class="polygon unmarked" />
                                    <polygon id="root-2" points="8,24, 12,40 16,24" class="polygon unmarked" />
                                @endif
                            </svg>
                        </td>
                        @endforeach
                        <!-- Second half of upper arch -->
                        @foreach(['incisor', 'incisor', 'incisor', 'premolar-4', 'premolar-5', 'molar', 'molar', 'molar'] as $type)
                        <td>
                            <svg width="24" height="40" transform="scale(1,-1)" class="{{ $type }} cursor-pointer transition-all duration-200 hover:opacity-80">
                                @if($type === 'incisor')
                                    <polygon id="top" points="8,0 16,0 16,12 8,12" class="polygon unmarked" />
                                    <polygon id="left" points="0,8 8,8 8,16 0,16" class="polygon unmarked" />
                                    <polygon id="bottom" points="8,12 16,12 16,24 8,24" class="polygon unmarked" />
                                    <polygon id="right" points="16,8 24,8 24,16 16,16" class="polygon unmarked" />
                                    <polygon id="root-2" points="8,24, 12,40 16,24" class="polygon unmarked" />
                                @elseif($type === 'premolar-4')
                                    <polygon id="top" points="0,0 24,0 16,8 8,8" class="polygon unmarked" />
                                    <polygon id="left" points="0,0 8,8 8,16 0,24" class="polygon unmarked" />
                                    <polygon id="bottom" points="0,24 8,16 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="right" points="24,0 16,8 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="center" points="8,8, 16,8 16,16 8,16" class="polygon unmarked" />
                                    <polygon id="root-1" points="0,24, 4,40 8,24" class="polygon unmarked" />
                                    <polygon id="root-3" points="16,24, 20,40 24,24" class="polygon unmarked" />
                                @elseif($type === 'premolar-5')
                                    <polygon id="top" points="0,0 24,0 16,8 8,8" class="polygon unmarked" />
                                    <polygon id="left" points="0,0 8,8 8,16 0,24" class="polygon unmarked" />
                                    <polygon id="bottom" points="0,24 8,16 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="right" points="24,0 16,8 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="center" points="8,8, 16,8 16,16 8,16" class="polygon unmarked" />
                                    <polygon id="root-2" points="8,24, 12,40 16,24" class="polygon unmarked" />
                                @elseif($type === 'molar')
                                    <polygon id="top" points="0,0 24,0 16,8 8,8" class="polygon unmarked" />
                                    <polygon id="left" points="0,0 8,8 8,16 0,24" class="polygon unmarked" />
                                    <polygon id="bottom" points="0,24 8,16 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="right" points="24,0 16,8 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="center" points="8,8, 16,8 16,16 8,16" class="polygon unmarked" />
                                    <polygon id="root-1" points="0,24, 4,40 8,24" class="polygon unmarked" />
                                    <polygon id="root-2" points="8,24, 12,40 16,24" class="polygon unmarked" />
                                    <polygon id="root-3" points="16,24, 20,40 24,24" class="polygon unmarked" />
                                @endif
                            </svg>
                        </td>
                        @endforeach
                    </tr>
                    
                    <!-- Lower Arch Tooth Numbers -->
                    <tr class="text-[10px] font-bold text-base-content/40 pt-4">
                        <td>48</td><td>47</td><td>46</td><td>45</td><td>44</td><td>43</td><td>42</td><td>41</td>
                        <td>31</td><td>32</td><td>33</td><td>34</td><td>35</td><td>36</td><td>37</td><td>38</td>
                    </tr>
                    <!-- Lower Arch SVGs -->
                    <tr>
                        @foreach(['molar', 'molar', 'molar', 'premolar-5', 'premolar-5', 'incisor', 'incisor', 'incisor'] as $type)
                        <td>
                            <svg width="24" height="40" class="{{ $type }} cursor-pointer transition-all duration-200 hover:opacity-80">
                                @if($type === 'molar')
                                    <polygon id="top" points="0,0 24,0 16,8 8,8" class="polygon unmarked" />
                                    <polygon id="left" points="0,0 8,8 8,16 0,24" class="polygon unmarked" />
                                    <polygon id="bottom" points="0,24 8,16 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="right" points="24,0 16,8 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="center" points="8,8, 16,8 16,16 8,16" class="polygon unmarked" />
                                    <polygon id="root-1" points="0,24, 4,40 8,24" class="polygon unmarked" />
                                    <polygon id="root-2" points="8,24, 12,40 16,24" class="polygon unmarked" />
                                    <polygon id="root-3" points="16,24, 20,40 24,24" class="polygon unmarked" />
                                @elseif($type === 'premolar-5')
                                    <polygon id="top" points="0,0 24,0 16,8 8,8" class="polygon unmarked" />
                                    <polygon id="left" points="0,0 8,8 8,16 0,24" class="polygon unmarked" />
                                    <polygon id="bottom" points="0,24 8,16 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="right" points="24,0 16,8 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="center" points="8,8, 16,8 16,16 8,16" class="polygon unmarked" />
                                    <polygon id="root-2" points="8,24, 12,40 16,24" class="polygon unmarked" />
                                @elseif($type === 'incisor')
                                    <polygon id="top" points="8,0 16,0 16,12 8,12" class="polygon unmarked" />
                                    <polygon id="left" points="0,8 8,8 8,16 0,16" class="polygon unmarked" />
                                    <polygon id="bottom" points="8,12 16,12 16,24 8,24" class="polygon unmarked" />
                                    <polygon id="right" points="16,8 24,8 24,16 16,16" class="polygon unmarked" />
                                    <polygon id="root-2" points="8,24, 12,40 16,24" class="polygon unmarked" />
                                @endif
                            </svg>
                        </td>
                        @endforeach
                        <!-- Second half of lower arch -->
                        @foreach(['incisor', 'incisor', 'incisor', 'premolar-5', 'premolar-5', 'molar', 'molar', 'molar'] as $type)
                        <td>
                            <svg width="24" height="40" class="{{ $type }} cursor-pointer transition-all duration-200 hover:opacity-80">
                                @if($type === 'incisor')
                                    <polygon id="top" points="8,0 16,0 16,12 8,12" class="polygon unmarked" />
                                    <polygon id="left" points="0,8 8,8 8,16 0,16" class="polygon unmarked" />
                                    <polygon id="bottom" points="8,12 16,12 16,24 8,24" class="polygon unmarked" />
                                    <polygon id="right" points="16,8 24,8 24,16 16,16" class="polygon unmarked" />
                                    <polygon id="root-2" points="8,24, 12,40 16,24" class="polygon unmarked" />
                                @elseif($type === 'premolar-5')
                                    <polygon id="top" points="0,0 24,0 16,8 8,8" class="polygon unmarked" />
                                    <polygon id="left" points="0,0 8,8 8,16 0,24" class="polygon unmarked" />
                                    <polygon id="bottom" points="0,24 8,16 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="right" points="24,0 16,8 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="center" points="8,8, 16,8 16,16 8,16" class="polygon unmarked" />
                                    <polygon id="root-2" points="8,24, 12,40 16,24" class="polygon unmarked" />
                                @elseif($type === 'molar')
                                    <polygon id="top" points="0,0 24,0 16,8 8,8" class="polygon unmarked" />
                                    <polygon id="left" points="0,0 8,8 8,16 0,24" class="polygon unmarked" />
                                    <polygon id="bottom" points="0,24 8,16 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="right" points="24,0 16,8 16,16 24,24" class="polygon unmarked" />
                                    <polygon id="center" points="8,8, 16,8 16,16 8,16" class="polygon unmarked" />
                                    <polygon id="root-1" points="0,24, 4,40 8,24" class="polygon unmarked" />
                                    <polygon id="root-2" points="8,24, 12,40 16,24" class="polygon unmarked" />
                                    <polygon id="root-3" points="16,24, 20,40 24,24" class="polygon unmarked" />
                                @endif
                            </svg>
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex gap-4 justify-center">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 border border-base-300 bg-white rounded-sm"></div>
                <span class="text-[10px] uppercase opacity-50 font-bold">Unmarked</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 border border-base-300 bg-gray-500 rounded-sm"></div>
                <span class="text-[10px] uppercase opacity-50 font-bold">Marked (Procedure)</span>
            </div>
        </div>
    </div>
</div>

<style>
    .polygon {
        stroke: #333;
        stroke-width: 0.5px;
        transition: fill 0.2s ease;
    }
    .unmarked {
        fill: #fff;
    }
    .marked {
        fill: #6b7280; /* Gray-500 */
        stroke: #374151;
    }
    #dental-chart td {
        text-align: center;
        vertical-align: middle;
        padding: 4px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const polygonsArray = document.querySelectorAll('#dental-chart polygon');
        polygonsArray.forEach(polygon => {
            polygon.addEventListener('click', function(event) {
                this.classList.toggle('unmarked');
                this.classList.toggle('marked');
            });
        });
    });
</script>
