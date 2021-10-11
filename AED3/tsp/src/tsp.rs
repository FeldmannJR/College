use std::fmt::Debug;
use std::sync::Arc;
use std::sync::atomic::AtomicBool;

use petgraph::graph::UnGraph;
use petgraph::IntoWeightedEdge;

#[derive(Clone)]
pub struct TSPMatrix {
    pub dataset: usize,
    pub best_solution: usize,
    distances: Vec<Vec<usize>>,
}

#[derive(Clone, Debug)]
pub struct TSPSolution {
    pub forced_stop: bool,
    pub total_distance: usize,
    cities: Vec<usize>,
}

impl TSPSolution {
    pub fn force_stopped(&mut self) {
        self.forced_stop = true;
    }
}

pub trait ResolverTSP {
    fn resolve(matrix: TSPMatrix, keep_executing: Arc<AtomicBool>) -> TSPSolution;
}

impl TSPMatrix {
    #[allow(dead_code)]
    pub fn print(&self) {
        for x in self.distances.iter() {
            println!("{:?}", x);
        }
    }
}

impl Into<UnGraph<usize, usize>> for TSPMatrix {
    fn into(self) -> UnGraph<usize, usize> {
        let mut edges = vec![];
        let mut elements = vec![];
        for i in 0..self.cities_count() {
            elements.push(());
            for j in i + 1..self.cities_count() {
                edges.push((i as u32, j as u32, self.distance_between(i, j)).into_weighted_edge());
            }
        }
        UnGraph::from_edges(edges)
    }
}

impl TSPMatrix {
    pub fn new(dataset: usize, best_solution: usize, distances: Vec<Vec<usize>>) -> Self {
        TSPMatrix { dataset, best_solution, distances }
    }
    pub fn distance_between(&self, city_1: usize, city_2: usize) -> usize {
        self.distances
            .get(city_1)
            .and_then(|distances| distances.get(city_2))
            .expect("City not found!").clone()
    }
    pub fn cities_count(&self) -> usize {
        self.distances.len()
    }

    pub fn calculate_distance(&self, cities: &Vec<usize>) -> usize {
        let mut total_distance = 0;
        for i in 0..cities.len() - 1 {
            let from = cities[i];
            let to = cities[i + 1];
            total_distance += self.distance_between(from, to);
        }
        total_distance += self.distance_between(
            cities.last().unwrap().clone(),
            cities.first().unwrap().clone(),
        );
        total_distance
    }
    pub fn build_solution(&self, cities: Vec<usize>) -> TSPSolution {
        let total_distance = self.calculate_distance(&cities);
        return TSPSolution {
            forced_stop: false,
            total_distance,
            cities,
        };
    }
}
