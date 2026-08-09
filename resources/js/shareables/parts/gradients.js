import { fabric } from 'fabric';

const hex = '#2f3157';

export default {

    blueTransBottom: new fabric.Gradient({
        type: 'linear',
        coords: {
            x1: 0,
            y1: 0,
            x2: 0,
            y2: 1,
        },
        colorStops: [
            {offset: 0, color: hex, opacity: 0},
            {offset: 1, color: hex, opacity: 1}
        ]
    }),

    blueTransRight: new fabric.Gradient({
        type: 'linear',
        coords: {
            x1: 0,
            y1: 0,
            x2: 1,
            y2: 0
        },
        colorStops: [
            {offset: 0, color: hex, opacity: 0},
            {offset: 1, color: hex, opacity: 1}
        ]
    })
}
