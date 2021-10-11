use crate::tsp::{ResolverTSP, TSPMatrix, TSPSolution};
use itertools::Itertools;
use std::sync::atomic::AtomicBool;
use std::sync::atomic::Ordering::Relaxed;
use std::sync::Arc;

pub struct BruteForceAlgorithm;

impl ResolverTSP for BruteForceAlgorithm {
    fn resolve(matrix: TSPMatrix, keep_executing: Arc<AtomicBool>) -> TSPSolution {
        let cities = matrix.cities_count();
        let mut best_solution: Option<TSPSolution> = None;

        let permutations = (0..cities)
            .collect::<Vec<usize>>()
            .into_iter()
            .permutations(cities);
        for solution in permutations {
            // Timeout mechanism
            if !keep_executing.load(Relaxed) {
                let mut forced_solution = best_solution.unwrap();
                (&mut forced_solution).force_stopped();
                return forced_solution;
            }
            let distance = matrix.calculate_distance(&solution);
            if best_solution.is_none() || distance < best_solution.as_ref().unwrap().total_distance
            {
                best_solution = Some(matrix.build_solution(solution));
            }
        }

        return best_solution.unwrap();
    }
}
