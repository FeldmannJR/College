use crate::tsp::TSPMatrix;

static TSP_1: &str = include_str!("tsp1_253.txt");
static TSP_2: &str = include_str!("tsp2_1248.txt");
static TSP_3: &str = include_str!("tsp3_1194.txt");
static TSP_4: &str = include_str!("tsp4_7013.txt");
static TSP_5: &str = include_str!("tsp5_27603.txt");

pub fn load_tsp_matrix(tsp: usize) -> TSPMatrix {
    let content = match tsp {
        1 => TSP_1,
        2 => TSP_2,
        3 => TSP_3,
        4 => TSP_4,
        5 => TSP_5,
        _ => panic!("Invalid TSP number, provide between 1 and 5"),
    };

    let best = match tsp {
        1 => 253,
        2 => 1248,
        3 => 1194,
        4 => 7013,
        5 => 27603,
        _ => panic!("Invalid TSP number, provide between 1 and 5"),
    };

    let distances = content
        .lines()
        .map(|line| {
            line.split(" ")
                .filter_map(|str| {
                    if str.is_empty() {
                        return None;
                    }
                    return str.parse().ok();
                })
                .collect()
        })
        .collect();
    TSPMatrix::new(tsp,best,distances)
}
